

// モーダルを開く関数
function openModal() {
    document.getElementById('myModal').style.display = 'flex';
}

// モーダルを閉じる関数
function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

// モーダル外をクリックしても閉じるようにする（オプション）
window.onclick = function (event) {
    var modal = document.getElementById('myModal');
    if (event.target === modal) {
        closeModal();
    }
}

//ログアウト
function logout() {
    firebase.auth().onAuthStateChanged((user) => {
        if (!user) {
            console.log("ログインしていません");
            // logout.classList.add("hide");
            // return (false);
        } else {
            console.log("ログインしています");
        }
    });
    console.log("ログアウト実行");
    firebase.auth().signOut().then(function () {
        console.log("ログアウト成功");
        // 例: ログアウト後のリダイレクトなど
        window.location.href = '../login/index.html';
    }).catch(function (error) {
        console.error("ログアウトエラー:", error);
    });
}