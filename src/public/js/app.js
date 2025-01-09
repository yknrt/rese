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


// 編集モードを有効にする関数
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-btn');

    editButtons.forEach(function (editButton) {
        const container = editButton.closest('.form_group'); // 親要素を取得
        const saveButton = container.querySelector('.save-btn');
        const editForm = container.querySelector('.edit-form');
        const editDate = container.querySelector('.edit--date');

        // 編集ボタンのクリックイベント
        editButton.addEventListener('click', function () {
            const isEditing = container.getAttribute('data-editing') === 'true';
            if (isEditing) {
                editForm.style.display = 'none'; // 保存ボタンを非表示
                container.setAttribute('data-editing', 'false');
            } else {
                // editButton.style.display = 'none'; // 削除ボタンを非表示
                editForm.style.display = 'block'; // 保存ボタンを表示

                // 今日の日付を取得してmin属性に設定
                const today = new Date().toISOString().split('T')[0];
                editDate.setAttribute('min', today);

                container.setAttribute('data-editing', 'true');
            }
        });
    });
});
