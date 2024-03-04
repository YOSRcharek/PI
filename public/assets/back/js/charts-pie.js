// Récupérer les données des projets (en cours et terminés) depuis le serveur
fetch('/project/stats')
  .then(response => response.json())
  .then(data => {
    // Créer la configuration du graphique en utilisant les données récupérées
    const pieConfig = {
      type: 'doughnut',
      data: {
        datasets: [
          {
            data: [data.completedProjectsCount, data.ongoingProjectsCount],
            backgroundColor: ['#422006', '#713f12'],
            label: 'Projets',
          },
        ],
        labels: ['Terminé', 'En cours'],
      },
      options: {
        responsive: true,
        cutoutPercentage: 80,
        legend: {
          display: false,
        },
      },
    };

    // Sélectionner l'élément canvas
    const pieCtx = document.getElementById('pie');

    // Créer le graphique avec la configuration
    window.myPie = new Chart(pieCtx, pieConfig);
  })
  .catch(error => {
    console.error('Une erreur s\'est produite lors de la récupération des données :', error);
  });
