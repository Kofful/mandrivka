@extends('layouts.app')

@section('title', Request::is('searchtour') ? 'Поиск туров Mandrivka' : 'Горящие туры Mandrivka')
@section('content')
<div style='background-color: rgba(173, 216, 230, 0.2); margin-right:20px; margin-left:20px;'>
    <div>
        <input name="page" value="searchtour" style='display:none;'>
        <input id="hot" name="hot" value="{{Request::is('searchtour') ? 0 : 1}}" style='display:none'>
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
            <div class="form-div">
                <select class="custom-select none-outline" style='width:200px;' id="nutrition" name="nutrition">
                    <option value="0">Любой тип питания</option>
                    <?php
                    foreach ($nutrition as $row) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-div">
                <select class="custom-select none-outline" style='width:200px;' id="room-type" name="room-type">
                    <option value="0">Любой тип номера</option>
                    <?php
                    foreach ($room_types as $row) {
                        echo "<option value='" . $row['id'] . "'>" . $row['type'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <script>
                let i = 0;

                function onHeadsDown() {
                    let dropdownheads = $('#dropdownheads');
                    if (dropdownheads.css('height') === '0px') {
                        i = 1;
                    }
                }

                function onHeadsClick() {
                    if (i === 0) {
                        $('#dropdownheads-button').blur();
                    } else i = 0;
                }

                function onAdultsClick(element) {
                    let countdiv = $("#adults-count");
                    let count = parseInt(countdiv.text());
                    if (element === '+') {
                        if (count === 1) {
                            $("#adults-minus").removeClass("inactive");
                        }
                        if (count === 19) {
                            $("#adults-plus").addClass("inactive");
                        }
                        if (count < 20)
                            countdiv.text(++count);
                    } else {
                        if (count === 2) {
                            $("#adults-minus").addClass("inactive");
                        }
                        if (count === 20) {
                            $("#adults-plus").removeClass("inactive");
                        }
                        if (count > 1)
                            countdiv.text(--count);
                    }
                    $("#adults").attr("value", count);
                }

                function onChildrenClick(element) {
                    let countdiv = $("#children-count");
                    let count = parseInt(countdiv.text());
                    if (element === '+') {
                        if (count === 0) {
                            $("#children-minus").removeClass("inactive");
                        }
                        if (count === 9) {
                            $("#children-plus").addClass("inactive");
                        }
                        if (count < 10) {
                            count++;
                            $("#children-container").append("<select class='custom-select none-outline' style='margin: 5px;width: 120px;' name='child" + count + "' id='child" + count + "'>" +
                                "<option value='0'>до года</option>" +
                                "<option value='1'>1 год</option>" +
                                "<option value='2'>2 года</option>" +
                                "<option value='3'>3 года</option>" +
                                "<option value='4'>4 года</option>" +
                                "<option value='5'>5 лет</option>" +
                                "<option value='6'>6 лет</option>" +
                                "<option value='7'>7 лет</option>" +
                                "<option value='8'>8 лет</option>" +
                                "<option value='9'>9 лет</option>" +
                                "<option value='10'>10 лет</option>" +
                                "<option value='11'>11 лет</option>" +
                                "<option value='12'>12 лет</option>" +
                                "<option value='13'>13 лет</option>" +
                                "<option value='14'>14 лет</option>" +
                                "<option value='15'>15 лет</option>" +
                                "<option value='16'>16 лет</option>" +
                                "<option value='17'>17 лет</option>" +
                                "</select>");
                            countdiv.text(count);
                        }
                    } else {
                        if (count === 1) {
                            $("#children-minus").addClass("inactive");
                        }
                        if (count === 10) {
                            $("#children-plus").removeClass("inactive");
                        }
                        if (count > 0) {
                            $("#children-container select").last().remove();
                            countdiv.text(--count);
                        }
                    }
                    $("#children").attr("value", count);
                }
            </script>
            <div tabindex="0" id="dropdownheads-container" class="form-div">
                <button id="dropdownheads-button" type="button" onmousedown="onHeadsDown()" onclick="onHeadsClick()"
                        class="btn none-outline dropdownheads custom-select">Кто поедет
                </button>
                <div id="dropdownheads">
                    <h6>Взрослые:</h6>
                    <div class="adults-container">
                        <input id="adults" name="adults"
                               value="2" style='display:none;'>
                        <div id="adults-minus" onclick="onAdultsClick('-')"
                             class="adults-change">
                            -
                        </div>
                        <div id="adults-count"> 2</div>
                        <div id="adults-plus" onclick="onAdultsClick('+')"
                             class="adults-change">
                            +
                        </div>
                    </div>
                    <h6>Дети:</h6>
                    <div class="children-container">
                        <input id="children" name="children"
                               value="0"
                               style='display:none;'>
                        <div id="children-minus" onclick="onChildrenClick('-')"
                             class="children-change inactive">
                            -
                        </div>
                        <div id="children-count">0</div>
                        <div id="children-plus" onclick="onChildrenClick('+')"
                             class="children-change">
                            +
                        </div>
                    </div>
                    <div id="children-container">
                    </div>
                </div>
            </div>
            <div class="daterange-div">
                <input id="daterange" class="custom-select" type="text" name="daterange"
                       value="<?php echo(date("Y.m.d") . " - " . date("Y.m.d", strtotime(date("m/d/Y") . "+5 days"))) ?>"/>
            </div>
            <script>
                $(function () {
                    $('input[name="daterange"]').daterangepicker({
                        opens: 'center',
                        maxSpan: {
                            days: 21
                        },
                        minDate: moment().format("YYYY.MM.DD"),
                        showCustomRangeLabel: false,
                        startOfWeek: 'monday',
                        locale: {
                            format: "YYYY.MM.DD",
                            separator: " - ",
                            applyLabel: "Подтвердить",
                            cancelLabel: "Отмена",
                            fromLabel: "С",
                            toLabel: "По",
                            customRangeLabel: "Custom",
                            weekLabel: "W",
                            daysOfWeek: [
                                "Вс",
                                "Пн",
                                "Вт",
                                "Ср",
                                "Чт",
                                "Пт",
                                "Сб",
                            ],
                            monthNames: [
                                "Январь",
                                "Февраль",
                                "Март",
                                "Апрель",
                                "Май",
                                "Июнь",
                                "Июль",
                                "Август",
                                "Сентябрь",
                                "Октябрь",
                                "Ноябрь",
                                "Декабрь"
                            ],
                            firstDay: 1,
                        }
                    }, function (start, end, label) {
                    });
                });
            </script>
            <script>
                let min_price = 0;
                let max_price = 100000;
                $(function () {
                    $("#pricerange").slider({
                        range: true,
                        min: 0,
                        max: 100000,
                        step: 100,
                        values: [min_price, max_price],
                        slide: function (event, ui) {
                            $("#amount").val(ui.values[0] + " - " + ui.values[1]);
                        }
                    });
                });
            </script>
            <div class="pricerange-div">
                <p>
                    <label for="amount">Цена:</label>
                    <input type="text" id="amount" name="pricerange" readonly
                           style="border:0; margin-left: 5px; color:#f6931f; font-weight:bold;"
                           value="0 - 100000">
                </p>
                <div id="pricerange"></div>
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
            let hot = $("#hot")[0].value;
            let nutrition = $("#nutrition")[0].value;
            let room_type = $("#room-type")[0].value;
            let adults = $("#adults")[0].value;
            let children = $("#children")[0].value;
            let child_ages = [];
            for (let i = 0; i < children; i++) {
                child_ages.push($("#child" + (i + 1))[0].value);
            }
            let daterange = $("#daterange")[0].value;
            let pricerange = $("#amount")[0].value;
            $.post('/tours', {
                country,
                state,
                hot,
                nutrition,
                room_type,
                adults,
                children,
                child_ages,
                daterange,
                pricerange,
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
                            "<div style='margin-left:10px;margin-top:5px;margin-right:10px;'>" +
                            "<a href='/hotels/" + hotel['hotel_id'] + "?room_id=" + hotel['id'] + "&daterange=" + daterange + "' class='title'>" + hotel['hotel'] + "</a>\n" +
                            "<div class='description'><p>" + (hotel['description'].length > 300 ? (hotel['description'].substring(0, 300) + "...") : hotel['description']) + "</p></div>\n" +
                            "<div class='info-container'>" +
                            "<div class='nutrition-container'><img class='image-nutrition' src='../images/nutrition.png'><p class='info-nutrition'>" + hotel['nutrition'] + "</p></div>" +
                            "<div class='roomtype-container'><img class='image-roomtype' src='../images/room.png'><p class='info-roomtype'>" + hotel['room_type'] + "</p></div>" +
                            "<div class='places-container'><img class='image-places' src='../images/places.png'><p class='info-places'>" + hotel['places'] + "</p></div>" +
                            "<p class='price'>" + Math.round(hotel['price']) + " грн</p>" +
                            "</div>" +
                            "</div>" +
                            "</div>");
                    })
                    ;
                } else {
                    hotels.append("<div class='img_no_tours'>" +
                        "<img src='../images/no_tours.png' style='width:400px;'>" +
                        "<h5>По данному запросу туры не найдены.</h5>" +
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
@endsection('content')
