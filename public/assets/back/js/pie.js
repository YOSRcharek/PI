const ctx = document.getElementById('myChart').getContext('2d');
const associationId = window.location.pathname.split('/').pop();

fetch('/profil/' + associationId)
  .then(response => {
    if (!response.ok) {
      throw new Error('La requête a échoué : ' + response.status);
    }
    return response.json();
  })
  .then(data => { 
    console.log('Données récupérées :', data);
    // Créez le graphique en utilisant les données JSON
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                label: 'Project',
                data: [data.completedProjectsCount, data.ongoingProjectsCount],
                backgroundColor: ['#422006', '#854d0e'],
            }],
            labels: ['Terminé', 'En cours'],
        },
        options: {
            cutoutPercentage: 80,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            elements: {
                arc: {
                    borderWidth: 0,
                    borderColor: 'transparent',
                }
            },
        }
    });
  })
  .catch(error => {
    console.error('Une erreur s\'est produite lors de la récupération des données :', error);
  });
