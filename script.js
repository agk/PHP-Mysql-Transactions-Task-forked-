const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");
const month_names = {
  "01": "January",
  "02": "Februarry",
  "03": "March",
};

function httpReq(URL, method, data, success, error) {
  var xhr = new XMLHttpRequest();
  xhr.open(method, URL, true);
  xhr.send(data);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        success(xhr.responseText);
      } else {
        if (error) error(xhr.status);
      }
    }
  };
}

submit_btn.onclick = function (e) {
  e.preventDefault();
  data_table.style.display = "block";

  // TODO: implement
  //alert("Not implemented");
  var userID = document.getElementById("user").value;

  // var data = "user=" + userID; // get
  // alert(data);
  //var data = { user: userID };
  var method = "GET";
  var url = "/data.php?user=" + userID;

  httpReq(url, method, data, function (res) {
    //console.log("response: ", res);
    data = JSON.parse(res);
    // console.log(data);
    //let selectObj = document.querySelector('[name=xfield\\[tarif\\]]');
    var id_selected = document.querySelector("#user").selectedIndex;
    var userName = document
      .querySelector("#user")
      [id_selected].getAttribute("name");

    var html =
      "<h2>Transactions of `" +
      userName +
      "`</h2>" +
      "<table><tr><th>Mounth</th><th>Amount</th></tr>";

    for (let prop in data) {
      // console.log(`${prop}: ${data[prop]}`);
      html +=
        "<tr><td>" +
        month_names[`${prop}`] +
        "</td><td>" +
        `${data[prop]}` +
        "</td></tr>";
    }
    html += "</table>";

    document.getElementById("data").innerHTML = html;
  });
};
