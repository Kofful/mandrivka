@extends('layouts.app')
@section('title', 'Поиск отелей Mandrivka')

@section('content')
<div style='background-color: rgba(173, 216, 230, 0.2); margin-right:20px; margin-left:20px;'>
    <div>
        <input name="page" value="searchtour" style='display:none;'>
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
                    );
                });
            }
        </script>
        <div class="form-container">
            <div class="form-search">
                <input type="text" class="form-control" placeholder="Название отеля" id="name" name="name"
                       onchange="onChangeCountry(this)" value=""/>
            </div>
            <div class="form-div">
                <select class="custom-select none-outline" style='width:200px;' id="countries" name="country"
                        onchange="onChangeCountry(this)">
                    <option value="0">Все страны</option>
                    <?php
                    foreach ($countries as $country) {
                        echo "<option value='" . $country['id'] . "'>" . $country['country'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-div">
                <select class="custom-select none-outline" style='width:200px;' id="states" name="state">
                    <option value="0">Все города</option>
                </select>
            </div>
        </div>
        <button style='margin:10px;' onclick="submitParams()" class='btn btn-primary'>Поиск</button>
    </div>
</div>
<div id="hotel-container">
    <script>
        let page = 0;

        function onLoadMore() {
            $("#loadMore").remove();
            $("#hotel-container").append("<div id=\"loader\" style=\"display: flex; justify-content: center;\"><div class=\"loader\"></div></div>");
            let country = $("#countries")[0].value;
            let state = $("#states")[0].value;
            let name = $("#name")[0].value;
            $.post('/hotels', {
                country,
                state,
                name,
                page
            }, function (data) {
                page++;
                data = JSON.parse(data);
                let hotels = $('#hotel-container');
                $('#loader')[0].remove();
                if (data != null && data.length !== 0) {
                    if (data.length === 10) {
                        hotels.after("<div id='loadMore' style='display:flex;justify-content: center;'><button onclick='onLoadMore()' class='button-more btn btn-outline-warning'>ЕЩЕ</button></div>");
                    }
                    data.forEach(function (hotel) {
                        hotels.append("<div class='list-item'>\n" +
                            "<img src='../images/uploads/" + hotel["path"] + "' style='min-width:200px;width:200px;height:133px;align-self:center'>" +
                            "<div style='margin-left:10px;margin-top:5px;margin-right:10px; width:100%;'>" +
                            "<a href='/hotels/" + hotel['id'] + "' class='title'>" + hotel['hotel'] + "</a>\n" +
                            "<p class='description'>" + (hotel['description'].length > 300 ? (hotel['description'].substring(0, 300) + "...") : hotel['description']) + "</p>\n" +
                            "<div class='hotels-info-container'>" +
                            "<div class='rating-container'><img class='image-rating' src='../images/star.png'><p class='info-rating'>" + "Рейтинг: " + hotel['rating'] + "</p></div>" +
                            "<div class='hotels-places-container'><img class='image-places' src='../images/room.png'><p class='hotels-info-places'>" + "Свободно: " + hotel['free_places'] + "</p></div>" +
                            "<div class='price-container'><p class='info-price'><img class='image-price' src='../images/cash.png'>" + "Цена: от " + Math.round(hotel['price']) + " грн/сутки</p>" +
                            "</div>" +
                            "</div>" +
                            "</div>" +
                            "</div>");
                    });
                } else {
                    hotels.append("<div class='img_no_tours'>" +
                        "<img src='../images/no_hotels.png' style='height:300px;'>" +
                        "<h5>По данному запросу отели не найдены.</h5>" +
                        "</div>");
                }
            });
        }
        onLoadMore();
        function submitParams() {
            $("#hotel-container").empty();
            page = 0;
            onLoadMore();
        }
    </script>
</div>
@endsection
