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
        let state = $('#new_hotel_states')[0];
        let hotel = $('#hotel_name')[0];
        let min_age = $('#min_age')[0];
        let nutrition = $('#nutrition')[0];
        let hot = $('#checkbox_hot')[0];
        let description = CKEDITOR.instances.description_editor.getData();

        let data = new FormData();
        $.each($("#add-images")[0].files, function (key, value) {
            data.append('photo' + key, value);
        });
        data.append('state_id', state.value);
        data.append('hotel', hotel.value);
        data.append('min_age', min_age.value);
        data.append('nutrition', nutrition.value);
        data.append('hot', hot.value === "on" ? 1 : 0);
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
                console.log(data);
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


<!--TODO relocate logic to hotel page-->
<form action="index.php?page=admin" method="post"
      style="background-color:white;box-shadow:0 0 2px 2px rgba(0,0,0,0.1); margin:10px; padding:10px;">
    <label>Удалить отель:</label><br>
    <label>Страна</label>
    <script>
        function onChangeCountry1(country) {
            $.post('functions.php', {get_states: 0, country: country.value}, function (data) {
                let states = $('#states2')[0].options;
                for (i = states.length; i > 0; i--) {
                    states[i] = null;
                }
                data = JSON.parse(data);
                states = $('#states2');
                data.forEach(function (state) {
                        states.append("<option value='" + state['id'] + "'>" + state['state'] + "</option>")
                    }
                )
                ;
            });
        }
    </script>
    <select id='countries1' name="country" onchange="onChangeCountry1(this)">
        <option value='0'>Выбрать страну</option>
    </select><br>
    <label>Город</label>
    <script>
        function onChangeState(state) {
            $.post('functions.php', {
                get_hotels: 0,
                state: state.value,
                country: $('#countries')[0]['value']
            }, function (data) {
                let hotels = $('#hotels')[0].options;
                for (i = hotels.length; i > 0; i--) {
                    hotels[i] = null;
                }
                data = JSON.parse(data);
                hotels = $('#hotels');
                data.forEach(function (hotel) {
                    console.log(hotel);
                    hotels.append("<option value='" + hotel['id'] + "'>" + hotel['hotel'] + "</option>")
                })
                ;
            });
        }
    </script>
    <select id="states2" name="state" onchange="onChangeState(this)">
        <option value='0'>Выбрать город</option>
        ;
        <?php
        if (isset($_POST['states1'])) {
            while ($row = mysqli_fetch_array($_POST['states1'])) {
                echo "<option value='" . $row['id'] . ($row['id'] == $_POST['state'] ? "'selected>" : "'>") . $row['state'] . "</option>";
            }
        }
        ?>
    </select><br>
    <label>Отель:</label>
    <select id="hotels" name="hotel">
        <?php
        if (isset($_POST['hotels'])) {
            while ($row = mysqli_fetch_array($_POST['hotels'])) {
                echo "<option value='" . $row['id'] . "'>" . $row['hotel'] . "</option>";
            }
        }
        ?>
    </select>
    <button type="submit" class="btn btn-danger" name="btn_hotel_del">Удалить отель</button>
</form>

@endsection
