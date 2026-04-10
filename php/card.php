<?php
// Подключение к базе данных
$conn = new mysqli("localhost", "root", "", "Udmurtia_t2");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$sql = "SELECT * FROM `card_route`";
$result = $conn->query($sql);

if (!$result) {
    die("Ошибка запроса: " . $conn->error);
}
?>

<?php
while($row = $result->fetch_assoc()) {
    $cardId = "card-" . $row["id"]; 
    
    // Подготавливаем данные для фильтрации
    $budget_value = ($row["budget"] == "Эконом") ? "lawful" : "medium";
    $type_value = str_replace(array('Пеший', 'Автомобильный', 'Автобусный'), array('pedestrian', 'car', 'bus'), $row["type"]);
    $category_value = str_replace(array('Этнографический', 'Исторический', 'Природный'), array('history_culture', 'active', 'nature'), $row["category"]);
    $animals_value = ($row["animals"] == "Да") ? "animals" : "without_animals";
    $child_value = ($row["child"] == "Да") ? "yes" : "no";
    
    // Собираем изображения в массив (только непустые)
    $images = array();
    if (!empty($row["img_1"])) $images[] = $row["img_1"];
    if (!empty($row["img_2"])) $images[] = $row["img_2"];
    if (!empty($row["img_3"])) $images[] = $row["img_3"];
    
    // Если нет изображений, используем заглушку
    if (empty($images)) {
        $images[] = 'img/default-route.jpg';
    }
    
    $imagesJson = json_encode($images);
    
    // Преобразуем Duration в читаемый формат
    $duration_display = $row["Duration"];
    if ($duration_display == 'более 3 дней') $duration_display = 'более 3 дней';
    
    echo '<div id="'.$cardId.'" 
               class="floating-card" 
               data-budget="'.$budget_value.'"
               data-type="'.$type_value.'"
               data-category="'.$category_value.'"
               data-animals="'.$animals_value.'"
               data-child="'.$child_value.'"
               data-name="'.htmlspecialchars($row["name"], ENT_QUOTES).'"
               data-desc="'.htmlspecialchars($row["description"], ENT_QUOTES).'"
               data-price="'.number_format($row["price"], 0, '', ' ').' ₽"
               data-rating="'.$row["rating"].'"
               data-reviews="'.$row["reviews_count"].'"
               data-images=\''.$imagesJson.'\'
               data-duration="'.$duration_display.'"
               data-category-name="'.$row["category"].'"
               data-type-name="'.$row["type"].'"
               data-budget-name="'.$row["budget"].'">';
    echo '  <img class="imagination" src="'.$row["img_1"].'" alt="'.$row["name"].'">';
    echo '  <div class="content">';
    echo '    <h3>'.$row["name"].'</h3>';
    echo '    <p class="desc">'.mb_substr($row["description"], 0, 100).'...</p>';
    echo '    <div class="info">';
    echo '      <div class="footer">';
    echo '        <div class="rating">';
    echo            $row["reviews_count"].' отзывов ';
    // Звезды рейтинга
    $rating = floatval($row["rating"]);
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            echo '<span class="star filled">★</span>';
        } elseif ($i - 0.5 <= $rating) {
            echo '<span class="star half">½</span>';
        } else {
            echo '<span class="star">☆</span>';
        }
    }
    echo '        </div>';
    echo '        <div class="price-row">';
    echo '          <span class="price">'.number_format($row["price"], 0, '', ' ').'₽ <small>за человека</small></span>';
    echo '          <div class="card-buttons">';
    echo '              <a href="#" class="btn-book" onclick="event.preventDefault(); openTourDetails(\''.$cardId.'\')">Подробнее</a>';
    echo '          </div>';
    echo '        </div>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}
$conn->close();
?>