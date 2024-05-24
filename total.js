function displayTotal() {
    // Récupération des données du tableau
    const table = document.getElementById("table");
    const rows = table.getElementsByTagName("tr");
  
    // Initialisation des compteurs
    let col4Count = 0;
    let col5Count = 0;
    let col6Count = 0;
    let col7Count = 0;
    let col8Count = 0;
    let col9Count = 0;
    let repasNonConsCount = 0;
  
    // Parcours du tableau pour compter les valeurs
    for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName("td");
      col4Count += Number(cells[3].textContent);
      col5Count += Number(cells[4].textContent);
      col6Count += Number(cells[5].textContent);
      col7Count += Number(cells[6].textContent);
      col8Count += Number(cells[7].textContent);
      col9Count += Number(cells[8].textContent);
      if (cells[9].textContent === "0") {
        repasNonConsCount++;
      }
    }
  
    // Calcul du total
    const total = col4Count + col5Count + col6Count + col7Count + col8Count + col9Count + repasNonConsCount;
  
    // Création du tableau HTML pour afficher les résultats
    const totalTable = document.createElement("table");
    const totalHeader = document.createElement("tr");
    const headerCols = ["Colonne", "Nombre de oui"];
    for (let i = 0; i < headerCols.length; i++) {
      const th = document.createElement("th");
      th.textContent = headerCols[i];
      totalHeader.appendChild(th);
    }
    totalTable.appendChild(totalHeader);
  
    // Ajout des données dans le tableau HTML
    const col4Row = document.createElement("tr");
    const col4Header = document.createElement("td");
    col4Header.textContent = "Colonne 4";
    const col4Data = document.createElement("td");
    col4Data.textContent = col4Count;
    col4Row.appendChild(col4Header);
    col4Row.appendChild(col4Data);
    totalTable.appendChild(col4Row);
  
    const col5Row = document.createElement("tr");
    const col5Header = document.createElement("td");
    col5Header.textContent = "Colonne 5";
    const col5Data = document.createElement("td");
    col5Data.textContent = col5Count;
    col5Row.appendChild(col5Header);
    col5Row.appendChild(col5Data);
    totalTable.appendChild(col5Row);
  
    const col6Row = document.createElement("tr");
    const col6Header = document.createElement("td");
    col6Header.textContent = "Colonne 6";
    const col6Data = document.createElement("td");
    col6Data.textContent = col6Count;
    col6Row.appendChild(col6Header);
    col6Row.appendChild(col6Data);
    totalTable.appendChild(col6Row);
  
    const col7Row = document.createElement("tr");
    const col7Header = document.createElement("td");
    col7Header.textContent = "Colonne 7";
    const col7Data = document.createElement("td");
    col7Data.textContent = col7Count;
    col7Row.appendChild(col7Header);
    col7Row.appendChild(col7Data);
    totalTable.appendChild(col7Row);
  
    const col8Row = document.createElement("tr");
    const col8Header = document.createElement
}