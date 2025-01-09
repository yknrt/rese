function generateTimeRange(startHour, startMinute, endHour, endMinute, intervalMinutes) {
    const start = new Date();
    start.setHours(startHour, startMinute, 0, 0); // 開始時刻を設定
    const end = new Date();
    end.setHours(endHour, endMinute, 0, 0); // 終了時刻を設定

    const intervalMilliseconds = intervalMinutes * 60 * 1000; // インターバルをミリ秒に変換
    const times = [];

    for (let time = start.getTime(); time <= end.getTime(); time += intervalMilliseconds) {
        newTime = new Date(time);
        times.push(newTime.toLocaleTimeString().slice(0, -3)); // タイムスタンプをDateオブジェクトに変換して配列に追加
    }
    return times;
}

function inputToSelect(inputElement, options) {
    // 元の値を取得
    const inputValue = inputElement.value;
    // 新しいselectタグを作成
    const select = document.createElement('select');
    select.name = inputElement.name;
    select.className = inputElement.className;
    select.id = inputElement.id;
    options.forEach(function(optionText) {
        const option = document.createElement('option');
        option.value = optionText;
        if (typeof optionText === 'number') {
            option.textContent = `${optionText}人`;
        } else {
            option.textContent = optionText;
        }

        // 元の値が選択肢に一致する場合、選択状態にする
        if (option.textContent == inputValue) {
            option.selected = true;
        }
        select.appendChild(option);
    });
    // 既存のinputタグをselectタグに置き換え
    inputElement.parentNode.replaceChild(select, inputElement);
    // return inputElement;
}

function selectToInput(select, inputValue) {
    const input = document.createElement('input');
    input.type = 'text';
    input.value = inputValue; // 選択された値を取得
    input.className = select.className;
    input.name = select.name;
    input.id = select.id;
    // inputタグを再生成して置き換える
    select.parentNode.replaceChild(input, select);
    input.setAttribute('readonly', 'readonly');
}


// 編集モードを有効にする関数
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-btn');

    editButtons.forEach(function (editButton) {
        const container = editButton.closest('.form__group'); // 親要素を取得
        const deleteButton = container.querySelector('.cancel-btn');
        const saveButton = container.querySelector('.save-btn');
        const inputDate = container.querySelector('.input-date');
        const inputTime = container.querySelector('.input-time');
        const inputNumber = container.querySelector('.input-number');

        // 編集ボタンのクリックイベント
        editButton.addEventListener('click', function () {
            const isEditing = container.getAttribute('data-editing') === 'true';
            if (isEditing) {
                saveButton.style.display = 'none'; // 保存ボタンを非表示
                deleteButton.style.display = 'inline-block'; // 削除ボタンを表示
                // inputを再び読み取り専用に
                inputDate.value = inputDate.dataset.originalValue;
                inputDate.setAttribute('readonly', 'readonly');

                // selectをinputに置き換える
                const selectTime = container.querySelector('.input-time');
                const inputTimeValue = container.getAttribute('time-original-value'); // 元の値を取得
                selectToInput(selectTime, inputTimeValue);
                const selectNumber = container.querySelector('.input-number');
                const inputNumberValue = container.getAttribute('number-original-value'); // 元の値を取得
                selectToInput(selectNumber, inputNumberValue);
                container.setAttribute('data-editing', 'false');
            } else {
                // 元の値を保存
                inputDate.dataset.originalValue = inputDate.value; // 元の値を保存
                const inputTimeValue = inputTime.value
                container.setAttribute('time-original-value', inputTimeValue);
                const inputNumberValue = inputNumber.value
                container.setAttribute('number-original-value', inputNumberValue);

                editButton.style.display = 'none'; // 削除ボタンを非表示
                deleteButton.style.display = 'none'; // 削除ボタンを非表示
                saveButton.style.display = 'inline-block'; // 保存ボタンを表示

                // 今日の日付を取得してmin属性に設定
                const today = new Date().toISOString().split('T')[0];
                inputDate.setAttribute('min', today);
                // inputを編集可能に
                inputDate.removeAttribute('readonly');
                inputDate.focus();

                //inputタグをselectタグに置き換える
                const timeOptions = generateTimeRange(10, 0, 23, 30, 30); // 選択肢を追加
                inputToSelect(inputTime, timeOptions);
                const numberOptions = Array.from({ length: 20 }, (_, i) => i + 1);
                inputToSelect(inputNumber, numberOptions);
                container.setAttribute('data-editing', 'true');
            }
        });

        // // 保存ボタンのクリックイベント
        // saveButton.addEventListener('click', function () {
        //     saveButton.style.display = 'none'; // 保存ボタンを非表示
        //     editButton.style.display = 'inline-block'; // 編集ボタンを再表示

        //     // inputを再び読み取り専用に
        //     inputDate.setAttribute('readonly', 'readonly');

        //     // selectをinputに置き換える
        //     const selectTime = container.querySelector('.input-time');
        //     selectToInput(selectTime);
        //     const selectNumber = container.querySelector('.input-number');
        //     selectToInput(selectNumber);
        //     // 必要に応じてデータ保存の処理を追加
        //     console.log('保存処理を実行');
        // });
    });
});
