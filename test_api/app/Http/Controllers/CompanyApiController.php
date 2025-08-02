<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyActivityRepository;
use App\Repositories\CompanyRepository;
use App\Services\ActivityService;
use App\Services\BuildingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="TEST API",
 *     description="API документация ТЗ"
 * )
 *
 * @OA\Tag(
 *     name="Companies",
 *     description="Companies"
 * )
 *
 * @OA\Tag(
 *     name="Activities",
 *     description="Activities"
 * )
 *
 * @OA\Schema(
 *     schema="Company",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Example Company"),
 *     @OA\Property(property="building_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="integer", example="2025-08-01T16:48:18.000000Z"),
 *     @OA\Property(property="updated_at", type="integer", example="2025-08-01T16:48:18.000000Z"),
 * )
 * @OA\SecurityScheme(
 *      securityScheme="api_key",
 *      type="apiKey",
 *      in="header",
 *      name="token"
 *  )
 * @OA\OpenApi(
 *      security={{"api_key": {}}}
 *  )
 */
class CompanyApiController extends Controller
{
    private CompanyRepository $companyRepository;
    private CompanyActivityRepository $companyActivityRepository;
    private ActivityService $activityService;
    private BuildingService $buildingService;
    public function __construct(
        CompanyRepository $companyRepository,
        CompanyActivityRepository $companyActivityRepository,
        ActivityService $activityService,
        BuildingService $buildingService
    ) {
        $this->companyRepository = $companyRepository;
        $this->companyActivityRepository = $companyActivityRepository;
        $this->activityService = $activityService;
        $this->buildingService = $buildingService;
    }

    /**
     * @OA\Get(
     *     path="/api/companies",
     *     tags={"Companies"},
     *     summary="Получить список организаций",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Название организации",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Тип выборки(1- круг, 2 - квадрат)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Радиус поиска (градусов)",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         description="Долгота",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         description="Широта",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="companies",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Company")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $name = $request->query('name');
        $type = $request->query('type');
        $radius = $request->query('radius');
        $longitude = $request->query('longitude');
        $latitude = $request->query('latitude');

        if ($latitude && $longitude && $radius) {
            $buildings = $this->buildingService->locationBuilding($type, $radius, $longitude, $latitude);
            $companies = $this->companyRepository->getByBuildingIds(array_column($buildings->toArray(), 'id'));
        } elseif ($name) {
            $companies = $this->companyRepository->getByName($name);
        } else {
            $companies = $this->companyRepository->getAll();
        }

        return response()->json(['companies' => $companies]);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Получить информацию об организации",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID организации",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="company",
     *                 ref="#/components/schemas/Company"
     *             )
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $company = $this->companyRepository->get($id);
        return response()->json(['company' => $company]);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{id}/buildings",
     *     tags={"Companies"},
     *     summary="Получить организации в здании",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="companies",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Company")
     *             )
     *         )
     *     )
     * )
     */
    public function buildings($id): JsonResponse
    {
        $companies = $this->companyRepository->getByBuildingId($id);
        return response()->json(['companies' => $companies]);
    }

    /**
     * @OA\Get(
     *     path="/api/activities/{id}/companies",
     *     tags={"Activities"},
     *     summary="Получить компании по виду деятельности",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="companies",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Company")
     *             )
     *         )
     *     )
     * )
     */
    public function companiesByActivity($id): JsonResponse
    {
        $companyActivities = $this->companyActivityRepository->getByActivityId($id);
        $companies = $companyActivities->pluck('company')->all();
        return response()->json(['companies' => $companies]);
    }

    /**
     * @OA\Get(
     *     path="/api/activities/{id}/companies/with-children",
     *     tags={"Activities"},
     *     summary="Получить организации по виду деятельности включая дочерние",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID родительского вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="companies",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Company")
     *             )
     *         )
     *     )
     * )
     */
    public function companiesByParentActivity($id): JsonResponse
    {
        $activityIds = array_merge($this->activityService->getActivityTree($id), [$id]);
        $companyActivities = $this->companyActivityRepository->getByActivityIds($activityIds);
        $companies = $companyActivities->pluck('company')->all();
        return response()->json(['companies' => $companies]);
    }
}
