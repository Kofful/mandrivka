@extends('layouts.app')

@section('title', $hotel['hotel'])

@section('content')
<div class="hotel-container">
    <div class="title-container">
        <p class='hotel-title'><?php
            echo $hotel['hotel']; ?></p>
        @if(Auth::user() && Auth::user()->is_admin)
        <script>
            function deleteHotel() {
                let url = window.location.pathname;
                let hotel = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
                $.ajax({
                    url: "/hotels/" + hotel,
                    type: "DELETE",
                    success: function (data) {
                        if (data) {
                            data = JSON.parse(data);
                            window.location.href = "/hotels/";
                        }
                    }
                });
            }
        </script>
        <div class="dropleft hotel-options">
            <img src="../images/dots.jpg" class="dropdown-toggle dropdown-img" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false"/>
            <div class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuButton">
                <button onclick="deleteHotel()" class="dropdown-item">Удалить</button>
            </div>
        </div>
        @endif
    </div>
    <?php
    echo "<p class='hotel-location'>" . $hotel['country'] . ", " . $hotel['state'] . "</p>";
    ?>
    <div class='image-container'>
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel"
             style="height:400px; width:600px;overflow: hidden;">
            <div class="carousel-inner">
                <?php
                for ($i = 0; $i < sizeof($hotel['photos']); $i++) {
                    echo "
					<div style='transition: 200ms !important;' class='carousel-item" . ($i == 0 ? " active" : "") . "'>
					<img style='height:400px;object-fit:cover;' src='../images/uploads/" . $hotel['photos'][$i]['path'] . "'' class='d-block w-100'>
					</div>";
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div>
            <script>
                function sendReservation() {
                    let params = (new URL(document.location)).searchParams;
                    let room_id = params.get("room_id");
                    let price = $('#tour_price span')[0].innerText;
                    let places = $('#tour_places span')[0].innerText;
                    let daterange = $('#tour_daterange span')[0].innerText;
                    let customer_name = $('#customer_name').val();
                    let customer_phone = $('#customer_phone').val();
                    $.post('/reservation', {
                        room_id,
                        price,
                        places,
                        daterange,
                        customer_name,
                        customer_phone
                    }, function (data) {
                        if (data) {
                            console.log(data);
                            $('#send-reservation-button').replaceWith("<div class='tour-accept-container'><p class='tour-accepted'>Мы Вам перезвоним!</p></div>");
                        }
                    });
                }
            </script>
            <div class='right-form' id='right-form'>
                <?php if (isset($_GET['room_id']) && isset($_GET['daterange'])) {
                    echo "
                <p class='title-form'>Оставить заявку</p>
                <p class='tour-info' id='tour_price'> " . "Цена: <span>" . $hotel['price'] . "</span> грн" . "</p>
                <p class='tour-info' id='tour_daterange'>" . "Время тура: <span>" . $daterange . "</span></p>
                <p class='tour-info' id='tour_places'> " . "Мест: <span>" . (isset($room['places']) ? $room['places'] : 0) . "</span></p>
                <label class='label-form'>Имя</label>
                <input class='form-control' placeholder='Введите имя' type='text' id='customer_name' required>
                <label class='label-form phone-request'>Телефон</label>
                <input class='form-control' type='tel' placeholder='Пример: 0660006600' pattern='\d{10}' minlength='10'
                       maxlength='10' id='customer_phone' required>
                <button class='btn btn-warning btn-form btn-send-request' id='send-reservation-button' onclick='sendReservation()'>Отправить</button>";
                } else {
                    echo "<p class='title-form'>Рейтинг</p>
                    <p class='tour-info'> " . "5" . "</p>
                    <p class='title-form'>Свободных номеров</p>
                    <p class='tour-info'>" . $hotel['empty_rooms'] . "/" . sizeof($hotel['rooms']) . "</p>
                    ";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="rooms-container">
        <p class='hotel-description-title'>Номера отеля <?php echo $hotel['hotel']; ?></p>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Тип номера</th>
                <th scope="col">Цена за сутки</th>
                <th scope="col">Мест</th>
                <th scope="col">Питание</th>
                <?php if (Auth::user() && Auth::user()->is_admin) {
                    echo "<th scope='col'></th>";
                } ?>
            </tr>
            </thead>
            <script>
                function deleteRoom(id) {
                    $.ajax({
                        url: '/room/' + id,
                        type: 'DELETE',
                        success: function (data) {
                            if (data) {
                                $('#room' + id).remove();
                            }
                        }
                    });
                }

                function addRoom() {
                    let room_type = $('#room-type');
                    let price = $('#price');
                    let places = $('#places');
                    let url = window.location.pathname;
                    let hotel = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
                    $.post('/room', {
                        type_id: room_type.val(),
                        price: price.val(),
                        places: places.val(),
                        hotel_id: hotel
                    }, function (data) {
                        if (data) {
                            room_type.val('0');
                            price.val('');
                            places.val('');
                            // let table_body = $('.table tbody')[0];
                            // table_body.append("<tr id='" + "room" + data['id'] + "'>
                            // <td>" . data['room_type'] ."</td>
                            // <td>" . data['price'] . "</td>
                            // <td>" . data['places'] . "</td>
                            // <td>" . ['nutrition'] . "</td>" . (((Auth::user() && Auth::user()->is_admin)) ? "<td><button class='btn btn-info btn-room' onclick='deleteRoom(" . $room['id'] . ")'>Удалить</button></td>" : "") . "</tr>";

                        }
                    });
                }
            </script>
            <tbody>
            <?php
            foreach ($hotel['rooms'] as $room) {
                echo "<tr id='" . "room" . $room['id'] . "'>
<td>" . $room['room_type'] . "</td>
<td>" . $room['price'] . "</td>
<td>" . $room['places'] . "</td>
<td>" . $hotel['nutrition'] . "</td>" . (((Auth::user() && Auth::user()->is_admin)) ? "<td><button class='btn btn-info btn-room' onclick='deleteRoom(" . $room['id'] . ")'>Удалить</button></td>" : "") . "</tr>";
            }
            if (Auth::user() && Auth::user()->is_admin) {
                echo "<tr>
<td>
<select class='custom-select none-outline' style='width:200px;' id='room-type' name='room-type'>
                    <option value='0'>Выбрать тип номера</option>";
                foreach ($room_types as $row) {
                    echo "<option value='" . $row['id'] . "'>" . $row['type'] . "</option>";
                }
                echo "</select></td>
<td>
<input type='text' id='price'>
</td>
<td>
<input type='text' id='places'>
</td>
<td></td>
<td>
<button class='btn btn-info btn-room' onclick='addRoom()'>Добавить</button>
</td>
</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <p class='hotel-description-title'>Описание отеля <?php echo $hotel['hotel']; ?></p>
    <div class='hotel-description'><?php echo nl2br($hotel['description']); ?></div>
    <p class='hotel-description-title'>Отзывы об отеле <?php echo $hotel['hotel']; ?></p>
    <div class='hotel-comments'>
        <div class='leave-comment'>
            @if(Auth::user())
            <p class='leave-comment-title'>Оставить отзыв</p>
            <div class="rating">
                <label>
                    <input type="radio" name="stars" value="1"/>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="2"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="3"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="4"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="5"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
            </div>
            <script>
                let grade = 0;
                $(':radio[name="stars"]').change(function () {
                    grade = this.value;
                });

                function leaveComment() {
                    let comm = $('.comment-area').val();
                    let url = window.location.pathname;
                    let hotel_id = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
                    if (comm === "")
                        return;
                    $.post('/comment',
                        {
                            grade,
                            comm,
                            hotel_id
                            //TODO check auth user in controller
                        },
                        function (data) {
                            let container = $('.leave-comment');
                            container.empty();
                            container.append("<p class='comment-alert'>Спасибо за отзыв!</p>");
                        });
                }
            </script>
            <textarea class='comment-area' placeholder='Напишите отзыв здесь...'></textarea>
            <button class="btn btn-info" onclick="leaveComment()">Отправить</button>
            @else
            <div class="not-logged-in">
                <p class="login-to-comment">Войдите или зарегистрируйтесь, чтобы оставить отзыв</p>
            </div>
            @endif
        </div>
        <div class='comments-container'>
            @if(sizeof($hotel['comments']))
            @foreach($hotel['comments'] as $comment)
            <div class='comment'>
                <div class='comment-head'>
                    <p class='comment-name'><?php echo $comment['user']; ?></p>
                    <span class='grade'>
                        <?php switch ($comment['grade']) {
                            case 1:
                                echo "★";
                                break;
                            case 2:
                                echo "★★";
                                break;
                            case 3:
                                echo "★★★";
                                break;
                            case 4:
                                echo "★★★★";
                                break;
                            case 5:
                                echo "★★★★★";
                        } ?>
                    </span>
                </div>
                <p class='comment-text'><?php echo $comment['comm'] ?></p>
            </div>
            @endforeach
            @else
            <p class='comment-alert'>Нет отзывов.</p>
            @endif
        </div>
    </div>
</div>
@endsection
