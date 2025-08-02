@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>Login Form</h2>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="token-field">Токен</label>
            <input type="text" class="form-control" id="token-field" name="token" readonly>
        </div>

        <button id="submit-btn" class="btn btn-primary">Submit</button>
    </div>
    <script>
        $(document).ready(function() {
            $('#submit-btn').click(function() {
                $('#error-message').hide();
                $('#success-message').hide();
                var formData = {
                    username: $('#username').val(),
                    password: $('#password').val(),
                    _token: '{{ csrf_token() }}'
                };
                $.ajax({
                    url: "{{ route('token.create') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function(response) {
                        if (response.token) {
                            $('#token-field').val(response.token);
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Произошла ошибка';
                        var errors = xhr.responseJSON;

                        if (errors && errors.errors) {
                            errorMessage = '';
                            $.each(errors.errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else if (errors && errors.message) {
                            errorMessage = errors.message;
                        }
                        $('#error-message').html(errorMessage).show();
                    }
                });
            });
        });
    </script>
@endsection
