//変数の宣言ゾーン
var currentpage = 1;
var title;
var main;
var username;
var time;
var tagarr = [];
var icon;
var totalpost;
var toukou_spase = document.getElementById('toukou_spase');

//現在のページ数を取得して一番下に表示されているページ移動の番号を変える
//URLパラメータを取得する。なかったらなにもしない
var searchParams = new URLSearchParams(window.location.search);
var category = searchParams.get("category");
console.log("cate:".category);
console.log(searchParams.has("page"));
if (searchParams.has("page")) {
  console.log(searchParams.get("page"));
  currentpage = searchParams.get("page");
}




//非同期でPHPに通信
$.ajax({
  type: "POST",
  url: "./ichiran.php",
  data: { currentpage: currentpage },
  data2: { category: category },
}).done(function (data) {
  // console.log("成功！");
  // console.log(data);
  // console.log(currentpage);

  var json = JSON.parse(data);

  //投稿の総数を取得する
  totalpost = json[0].totalpost;
  console.log(totalpost);

  //ページ移動の番号を書き換える×7回
  for (var i = -3; i < 4; i++) {
    var page = Number(currentpage) + i;
    var pageY = document.getElementById('page' + (i + 4));
    if (page < 1) {
      pageY.textContent = " ";
    } else if (((page - 1) * 10) < totalpost) {
      pageY.setAttribute('href', 'ichiran2.php?page=' + page);
      pageY.textContent = page;
    } else {
      pageY.textContent = " ";
    }
  }

});

//検索ボタンを押したときの処理
$('#serchbutton').on('click', function () {
  var serchTextBox = document.getElementById('serchtext');
  var serchText = serchTextBox.value;
  var checkbox = document.getElementById('checkbox');
  if (checkbox.checked) {
    //チェックボックスがチェックされていた場合
    window.location.href = 'ichiran2.php?serchText=' + serchText + '&page=1&status=checked';
  } else {
    //チェックボックスがチェックされていない場合
    window.location.href = 'ichiran2.php?serchText=' + serchText + '&page=1&status=notchecked';
  }
});

//ページ移動ボタンの◀を押したときの処理
$('#pageback').on('click', function () {
  if (currentpage != 1) {
    window.location.href = 'ichiran2.php?page=' + (Number(currentpage) - 1);
  }
});

//ページ移動ボタンの▶を押したときの処理
$('#pagenext').on('click', function () {
  if ((currentpage * 10) < totalpost) {
    window.location.href = 'ichiran2.php?page=' + (Number(currentpage) + 1);
  }
});



