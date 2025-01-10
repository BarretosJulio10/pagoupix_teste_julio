
initChartFinances();
initChartClientsExpired();

function initChartFinances(){
    
    function getDataFinanceLine(){
        
        var response = null;
        var responseTextValue = null;
        
        $.ajax({
        	type: "POST",   
        	url: 'model/controller/finances/getChart.php',   
        	async: false,
        	data: {
                "typeChart": "financesMovLine"
            },
        	success : function(data) {
        		// Here you can specify that you need some exact value like responseText
        		responseTextValue = data.responseText;
        	    response = data;
        	}
        });
        
        return JSON.parse(response);

    }
    
   dataFinanceLine = getDataFinanceLine();
    
    // Define data set for all charts
    let entradas  = dataFinanceLine.entradas;
    let saidas    = dataFinanceLine.saidas;
    let mesesView = dataFinanceLine.mes_view;
    
    myData = {
            labels: mesesView,
            datasets: [
              {
                label: "Entradas",
                fill: true,
                backgroundColor: 'rgba(0, 173, 161, 0.25)',
                borderColor: 'rgba(0, 173, 156, 1)',
                data: entradas,
              },
                {
                label: "Saídas",
                fill: true,
                backgroundColor: 'rgba(255, 122, 78, 0.25)',
                borderColor: 'rgba(255, 122, 78, 1)',
                data: saidas,
            }]
        };
    
    // Default chart defined with type: 'line'
    Chart.defaults.global.defaultFontFamily = "monospace";
    var ctx = document.getElementById('financesMovLine').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: myData
    });

}

function initChartClientsExpired(){
    
    function getDataClientsExpired(){
        
        var response = null;
        var responseTextValue = null;
        
        $.ajax({
        	type: "POST",   
        	url: 'model/controller/signatures/getChart.php',   
        	async: false,
        	data: {
                "typeChart": "signaturesExpiredOnLive"
            },
        	success : function(data) {
        		// Here you can specify that you need some exact value like responseText
        		responseTextValue = data.responseText;
        	    response = data;
        	}
        });
        
        return JSON.parse(response);

    }
    
   dataClientsExpired = getDataClientsExpired();
   
    // Define data set for all charts
    let actives  = dataClientsExpired.actives;
    let expireds = dataClientsExpired.expireds;

    myData = {
             labels: [
                'Ativos',
                'Expirados'
              ],
            datasets: [
              {
                labels: [
                    'Ativos',
                    'Expirados'
                ],
                fill: true,
                // borderColor: 'rgba(0, 173, 156, 1)',
                data: [actives,expireds],
                backgroundColor: [
                  'rgba(0, 173, 156, 1)',
                  'rgba(255, 122, 78, 1)'
                ]
              }]
        };
    
    // Default chart defined with type: 'line'
    Chart.defaults.global.defaultFontFamily = "monospace";
    var ctx = document.getElementById('chartClients').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: myData
    });

}

