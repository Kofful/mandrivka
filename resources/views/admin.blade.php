@extends('layouts.app')
@section('title', 'Администрирование Mandrivka')
@section('content')
<script>
    function onChangeCountry(country) {
        $.post('/states', {country: country.value}, function (data) {
            let states = $('#states')[0].options;
            for (i = states.length; i > 0; i--) {
                states[i] = null;
            }
            data = JSON.parse(data);
            states = $('#states');
            data.forEach(function (state) {
                    states.append("<option value='" + state['id'] + "'>" + state['state'] + "</option>")
                }
            )
            ;
        });
    }

    function onChangeCountryForNewHotel(country) {
        $.post('/states', {country: country.value}, function (data) {
            let states = $('#new_hotel_states')[0].options;
            for (i = states.length; i > 0; i--) {
                states[i] = null;
            }
            data = JSON.parse(data);
            states = $('#new_hotel_states');
            data.forEach(function (state) {
                    states.append("<option value='" + state['id'] + "'>" + state['state'] + "</option>")
                }
            )
            ;
        });
    }

    function addCountry() {
        let country = $('#add_country')[0];
        $.post('/country', {country: country.value}, function (data) {
            if (data) {
                data = JSON.parse(data);
                country.value = "";
                $('#delete_country').append("<option value='" + data['id'] + "'>" + data['country'] + "</option>");
                $('#add_state_country').append("<option value='" + data['id'] + "'>" + data['country'] + "</option>");
                $('#delete_state_country').append("<option value='" + data['id'] + "'>" + data['country'] + "</option>");
                $('#countries').append("<option value='" + data['id'] + "'>" + data['country'] + "</option>");
            }
        });
    }

    function deleteCountry() {
        let country = $('#delete_country')[0];
        $.ajax({
            url: '/country/' + country.value,
            type: 'DELETE',
            success: function (data) {
                if (data) {
                    data = JSON.parse(data);
                    $('#delete_country').val('0');
                    $('#delete_country option[value="' + data['id'] + '"').remove();
                    $('#add_state_country option[value="' + data['id'] + '"').remove();
                    $('#delete_state_country option[value="' + data['id'] + '"').remove();
                    $('#countries option[value="' + data['id'] + '"').remove();
                }
            }
        });
    }

    function addState() {
        let country = $('#add_state_country')[0];
        let state = $('#add_state')[0];
        $.post('/state', {country_id: country.value, state: state.value}, function (data) {
            if (data) {
                state.value = "";
            }
        });
    }

    function deleteState() {
        let state = $('#states')[0];
        $.ajax({
            url: '/state/' + state.value,
            type: 'DELETE',
            success: function (data) {
                if (data) {
                    data = JSON.parse(data);
                    $('#states').val('0');
                    $('#states option[value="' + data['id'] + '"').remove();
                }
            }
        });
    }

    function addHotel() {
        let admin_error = $("#admin-error");
        admin_error.text("");
        let state = $('#new_hotel_states')[0];
        let hotel = $('#hotel_name')[0];
        let min_age = $('#min_age')[0];
        let nutrition = $('#nutrition')[0];
        let hot = $('#checkbox_hot')[0];
        let description = CKEDITOR.instances.description_editor.getData();

        let data = new FormData();
        let files = $("#add-images")[0].files;
        if(!files.length) {
            admin_error.text("Фотографии не загружены.")
            return;
        }
        $.each(files, function (key, value) {
            data.append('photo' + key, value);
        });
        if(state.value == 0) {
            admin_error.text("Город не выбран.")
            return;
        }
        data.append('state_id', state.value);
        if(!hotel.value) {
            admin_error.text("Название не введено.")
            return;
        }
        data.append('hotel', hotel.value);
        if(!min_age.value) {
            admin_error.text("Минимальный возраст не введен.")
            return;
        }
        data.append('min_age', min_age.value);
        if(nutrition.value == 0) {
            admin_error.text("Тип питания не выбран.")
            return;
        }
        data.append('nutrition', nutrition.value);
        data.append('hot', hot.value === "on" ? 1 : 0);
        if(!description) {
            admin_error.text("Описание не введено.")
            return;
        }
        data.append('description', description);
        $.ajax({
            url: "hotel/",
            type: "POST",
            dataType: "text",
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            },
            error: function () {
            }
        });
    }

</script>
<div id="hotel-adding-form" style="background-color:white;box-shadow:0 0 2px 2px rgba(0,0,0,0.1); margin:10px; padding:10px;">
    <h3>Добавить отель</h3>
    <div class="form-group">
        <label>Страна</label>
        <select id='countries' name="country" class="form-control" onchange="onChangeCountryForNewHotel(this)">
            <option value='0'>Выбрать страну</option>
            <?php
            foreach ($countries as $country) {
                echo "<option value='" . $country['id'] . "'>" . $country['country'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="new_hotel_states">Город</label>
        <select id="new_hotel_states" name="state" class="form-control" required>
            <option value='0'>Выбрать город</option>
        </select>
    </div>
    <div class="form-group">
        <label for="hotel_name">Название:</label>
        <input id="hotel_name" type="text" name="hotel" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="min_age">Минимальный возраст:</label>
        <input id="min_age" type="number" name="min_age" class="form-control" min="0" max="18" required>
    </div>
    <div class="form-group">
        <label for="nutrition">Питание:</label>
        <select id="nutrition" name="nutrition" class="form-control none-outline">
            <option value='0'>Выбрать тип питания</option>
            <?php
                foreach($nutrition as $nutr) {
                    echo "<option value='" . $nutr['id'] . "'>" . $nutr['name'] . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <input id="checkbox_hot" type="checkbox" class="custom-checkbox" name="hot">
        <label for="checkbox_hot">Сохранить как горящий тур</label>
    </div>
    <div class="form-group">
        <label for="description_editor">Описание:</label>
        <textarea name="description" id="description_editor" rows="10" cols="80"></textarea>
    </div>
    <script>
        CKEDITOR.replace('description_editor');
    </script>
    <label id="upload-images-button" class="btn btn-info" for="add-images">Загрузить картинки</label>
    <input id="add-images" accept="image/*" type="file" name="image[]" multiple><br>
    <div id="adminCarousel" class="carousel slide" data-ride="carousel"
         style="height:0px; width:600px;overflow: hidden;">
        <div class="carousel-inner">

        </div>
        <a class="carousel-control-prev" href="#adminCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#adminCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <script>
        $("#add-images").change(
            function () {
                sendAjaxForm();
            }
        );

        function sendAjaxForm() {
            let data = new FormData();
            $.each($("#add-images")[0].files, function (key, value) {
                data.append('photo' + key, value);
            });
            $("#adminCarousel").height("400px");
            $('.carousel-inner').empty();
            $.ajax({
                url: "loadimages/",
                type: "POST",
                dataType: "text",
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function (response) {
                    let result = response.split(" ");
                    if (result.length === 1) {
                        $("#adminCarousel").height("0");
                    } else {
                        for (let i = 0; i < result.length - 1; i++) {
                            $('.carousel-inner').append("<div style='transition: 200ms !important;' class='carousel-item" + (i === 0 ? " active" : "") + "'><img style='object-fit: cover; width: 600px; height:400px;' src='data:image/png;base64," + result[i] + "'></div>");
                        }
                    }
                },
                error: function () {
                }
            });
        }</script>
    <p class="admin-error" id="admin-error"></p>
    <div id="admin-add-hotel">
        <button id="btn_add-hotel" type="submit" class="btn btn-primary" onclick="addHotel()">Добавить</button>
    </div>
</div>
<div class="admin-container">
    <div class="admin-form">
        <h5>Добавить страну</h5>
        <input type="text" id="add_country" class="form-control">
        <button type="submit" class="btn btn-primary form-control" onclick="addCountry()">Добавить</button>
    </div>

    <div class="admin-form">
        <h5>Удалить страну</h5>
        <select id="delete_country" class="form-control none-outline">
            <option value='0'>Выбрать страну</option>
            <?php
            foreach ($countries as $country) {
                echo "<option value='" . $country['id'] . "'>" . $country['country'] . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-danger form-control" onclick="deleteCountry()">Удалить</button>
    </div>
    <div class="admin-form">
        <h5>Добавить город</h5>
        <select id="add_state_country" class="form-control none-outline">
            <option value='0'>Выбрать страну</option>
            <?php
            foreach ($countries as $country) {
                echo "<option value='" . $country['id'] . "'>" . $country['country'] . "</option>";
            }
            ?>
        </select>
        <input type="text" id="add_state" class="form-control">
        <button type="submit" class="btn btn-primary form-control" onclick="addState()">Добавить</button>
    </div>

    <div class="admin-form">
        <h5>Удалить город</h5>
        <select id="delete_state_country" class="form-control none-outline" onchange="onChangeCountry(this)">
            <option value='0'>Выбрать страну</option>
            <?php
            foreach ($countries as $country) {
                echo "<option value='" . $country['id'] . "'>" . $country['country'] . "</option>";
            }
            ?>
        </select>
        <select id="states" name="state" class="form-control none-outline">
            <option value='0'>Выбрать город</option>
        </select>
        <button type="submit" class="btn btn-danger form-control" onclick="deleteState()">Удалить</button>
    </div>
</div>
<div class="applications-container">
    <p class='hotel-description-title'>Заявки на туры</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Клиент</th>
            <th scope="col">Телефон</th>
            <th scope="col">Страна</th>
            <th scope="col">Город</th>
            <th scope="col">Отель</th>
            <th scope="col">Номер</th>
            <th scope="col">Время отдыха</th>
            <th scope="col">Стоимость</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <script>
            function deleteApplication(id) {
                $.ajax({
                    url: '/application/' + id,
                    type: 'DELETE',
                    success: function (data) {
                        if (data) {
                            $('#application' + id).remove();
                        }
                    }
                });
            }
        </script>
        <tbody>
        <?php
        foreach($applications as $application) {
            echo "<tr id='" . "application" . $application['id'] . "'>
<td>" . $application['customer_name'] ."</td>
<td>" . $application['customer_phone'] . "</td>
<td>" . $application['country'] . "</td>
<td>" . $application['state'] . "</td>
<td>" . $application['hotel'] . "</td>
<td>" . $application['room'] . "</td>
<td>" . $application['daterange'] . "</td>
<td>" . $application['price'] . "</td>" . (((Auth::user() && Auth::user()->is_admin)) ? "<td><button class='btn btn-info btn-room' onclick='deleteApplication(" . $application['id'] . ")'>Готово</button></td>" : "") . "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
@endsection
