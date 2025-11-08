<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Форма обратной связи</title>
    <style>
        /* Стили для Iframe-ready */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 1.5rem;
            color: #172b4d;
            overflow-x: hidden; /* Убираем гориз. скролл */
        }
        .widget-container { max-width: 500px; margin: 0 auto; }
        h2 { font-size: 1.5rem; color: #091e42; margin-bottom: 1rem; }

        /* Стили формы */
        .form-group { margin-bottom: 1rem; }
        label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dfe1e6;
            border-radius: 4px;
            box-sizing: border-box; /* Важно для padding */
            font-size: 1rem;
        }
        input[type="file"] {
            font-size: 0.9rem;
        }
        textarea { min-height: 100px; }

        /* Кнопка */
        .btn-submit {
            background-color: #0052cc;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-submit:hover { background-color: #0065ff; }
        .btn-submit:disabled { background-color: #a5adba; cursor: not-allowed; }

        /* Сообщения */
        .message {
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
            display: none; /* Скрыты по умолчанию */
        }
        .message.success { background-color: #e3fcef; border: 1px solid #57d9a3; color: #006644; }
        .message.error { background-color: #ffebe6; border: 1px solid #ff7452; color: #bf2600; }
        .error-list { list-style-type: disc; padding-left: 20px; }
    </style>
</head>
<body>

<div class="widget-container">
    <h2>Свяжитесь с нами</h2>
    <p>Пожалуйста, заполните форму, и мы скоро с вами свяжемся.</p>

    <div id="message-success" class="message success">
        <strong>Спасибо!</strong> Ваша заявка успешно отправлена.
    </div>

    <div id="message-error" class="message error">
        <strong>Ошибка!</strong> Не удалось отправить заявку.
        <ul id="error-list" class="error-list"></ul>
    </div>

    <form id="ticket-form" enctype="multipart/form-data">

        <div class="form-group">
            <label for="customer_name">Ваше имя</label>
            <input type="text" id="customer_name" name="customer_name" required>
        </div>

        <div class="form-group">
            <label for="customer_email">Email</label>
            <input type="email" id="customer_email" name="customer_email" required>
        </div>

        <div class="form-group">
            <label for="customer_phone">Номер телефона</label>
            <input type="text" id="customer_phone" name="customer_phone" placeholder="+79001234567" required>
        </div>

        <hr style="border: 0; border-top: 1px solid #dfe1e6; margin: 1.5rem 0;">

        <div class="form-group">
            <label for="subject">Тема</label>
            <input type="text" id="subject" name="subject" required>
        </div>

        <div class="form-group">
            <label for="body">Сообщение</label>
            <textarea id="body" name="body" required></textarea>
        </div>

        <div class="form-group">
            <label for="files">Прикрепить файлы (до 5)</label>
            <input type="file" id="files" name="files[]" multiple>
        </div>

        <button type="submit" id="submit-button" class="btn-submit">Отправить</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('ticket-form');
        const submitButton = document.getElementById('submit-button');
        const successMessage = document.getElementById('message-success');
        const errorMessage = document.getElementById('message-error');
        const errorList = document.getElementById('error-list');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Блокируем кнопку
            submitButton.disabled = true;
            submitButton.textContent = 'Отправка...';

            // Сбрасываем сообщения
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';
            errorList.innerHTML = '';

            // Используем FormData для отправки файлов
            const formData = new FormData(form);

            fetch('/api/tickets', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then(response => {
                    // Cначала получаем JSON
                    return response.json().then(data => ({ status: response.status, body: data }));
                })
                .then(({ status, body }) => {
                    if (status === 201) {
                        // Успех
                        form.reset();
                        form.style.display = 'none'; // Скрываем форму
                        successMessage.style.display = 'block';
                    } else if (status === 422) {
                        // Ошибки валидации
                        handleValidationErrors(body.errors);
                    } else {
                        // Другие ошибки (500, Rate Limit из нашего правила)
                        handleGenericError(body.message || 'Произошла неизвестная ошибка.');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    handleGenericError('Ошибка сети или сервера.');
                })
                .finally(() => {
                    // Разблокируем кнопку, если форма не скрыта
                    if (form.style.display !== 'none') {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Отправить';
                    }
                });
        });

        function handleValidationErrors(errors) {
            errorMessage.style.display = 'block';
            for (const key in errors) {
                errors[key].forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
            }
        }

        function handleGenericError(message) {
            errorMessage.style.display = 'block';
            const li = document.createElement('li');
            li.textContent = message;
            errorList.appendChild(li);
        }
    });
</script>
</body>
</html>
