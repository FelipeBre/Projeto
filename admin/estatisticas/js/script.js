document.addEventListener('DOMContentLoaded', function () {
    fetch('data.php')
      .then(response => {
        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.erro) {
          console.error('Erro retornado pelo servidor:', data.erro);
          return;
        }
  
        // Exibir totais
        if (document.getElementById('totalProdutos')) {
          document.getElementById('totalProdutos').textContent = data.totalProdutos;
        }
        if (document.getElementById('totalPedidos')) {
          document.getElementById('totalPedidos').textContent = data.totalPedidos;
        }
  
        // Gráfico de pedidos por mês
        const labels = data.pedidosMensais.map(item => item.mes);
        const valores = data.pedidosMensais.map(item => item.total);
  
        const ctx = document.getElementById('graficoPedidos').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Pedidos por Mês',
              data: valores,
              backgroundColor: 'rgba(54, 162, 235, 0.6)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                stepSize: 1
              }
            }
          }
        });
      })
      .catch(error => {
        console.error('Erro ao carregar dados:', error);
      });
  });
  