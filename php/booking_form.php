<div class="booking-modal-content">
    <span class="close-x" onclick="closeModal('bookingModal')">&times;</span>
    <h2 class="name_bron">Бронирование поездки</h2>
    
    <div class="header-row" style="margin-bottom: 20px;">
        <button type="button" class="btn-add" id="add-tourist">+ Добавить туриста</button>
    </div>

    <div class="booking-container">
        <div id="tourists-list">
            <!-- Сюда JS добавит туристов -->
        </div>

        <div class="payment-info">
            <h3>Оплата</h3>
            <p>Тур: <span id="selected-tour-name">«Название»</span></p>
            <p>Туристов: <span id="tourists-count">0</span></p>
            <hr>
            <div class="total-section">
                <span>Итого:</span>
                <strong id="total-price">0</strong> ₽
            </div>
            <button class="btn-pay">Оплатить онлайн</button>
        </div>
    </div>
</div>
