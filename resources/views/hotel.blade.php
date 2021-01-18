@extends('layouts.app')
@section('title', $hotel['hotel'])

@section('content')
<div class="hotel-container">
    <div class="title-container">
        <p class='hotel-title'><?php echo $hotel['hotel']; ?></p>
        <?php if (Auth::user() && Auth::user()->is_admin) {
            echo '
<script>
function deleteHotel() {
    let url = new URL(window.location);
    let hotel = url.searchParams.get("id");
    console.log(hotel);
    $.post("index.php?page=admin", {
        btn_hotel_del: 0,
         hotel2
         },
          () => {
    window.location.href = "/index.php?page=hotels";
});
}
</script>
<div class="dropleft hotel-options">
            <img src="../images/nutrition.png" class="dropdown-toggle dropdown-img" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"/>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <button onclick="deleteHotel()" class="dropdown-item">Удалить</button>
                <a class="dropdown-item" href="#">Изменить</a>
            </div>
        </div>';
        } ?>
    </div>
    <?php
    echo "<p class='hotel-location'>" . $hotel['country'] . ", " . $hotel['state'] . "</p>"
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
    </div>
    <p class='hotel-description-title'>Описание отеля <?php echo $hotel['hotel']; ?></p>
    <div class='hotel-description'><?php echo nl2br($hotel['description']) ?></div>
    <form action="index.php?page=apply&id=<?php echo $hotel['id'] ?>" class='right-form' method="post">
        <p class='title-form'>Оставить заявку</p>
        <label class='label-form'>Имя</label>
        <input class='form-control' placeholder="Введите имя" type='text' required>
        <label class='label-form phone-request'>Телефон</label>
        <input class='form-control' type='tel' placeholder="Пример: 0660006600" pattern="\d{10}" minlength="10"
               maxlength="10" required>
        <button class='btn btn-warning btn-form btn-send-request'>Отправить</button>
        <!-- TODO check classes in styles and delete them
        <p class="hotel-price-add"><span class='hotel-price'> 1kk грн с человека за ночь.</p>-->
    </form>
</div>
@endsection
