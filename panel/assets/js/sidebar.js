$(".nav").sortable({
  update: function(event, ui) {
    const ul = document.querySelectorAll('.nav li');
    var dadosUl = [];
    for (let i = 0; i <= ul.length - 1; i++) {
         if(ul[i].id !== ""){
          const dados = new Object();
          dados.name = $("#" + ul[i].id + " a").text();
          dados.icon = $("#" + ul[i].id + " a i").attr("class");
          dados.link = $("#" + ul[i].id + " a").attr("href");
          dados.id   = ul[i].id;
          dadosUl[i] = JSON.stringify(dados);
         }
    }

    var dados = JSON.stringify(dadosUl);

    $.post(urlsite + '/panel/model/controller/sidebar/save.php', {
      dados: dados
    }, function(data) {
      try {
        const obj = JSON.parse(data);
        if (obj.erro) {
          nowuiDashboard.showNotification('danger', 'bottom', 'right', obj.message, 'now-ui-icons ui-1_bell-53');
        } else {
          nowuiDashboard.showNotification('success', 'bottom', 'right', obj.message, 'now-ui-icons ui-1_bell-53');
        }
      } catch (e) {
        console.log(e);
      }
    });
  }
});
