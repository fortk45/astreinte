//Lance le téléchargement du fichier CSV
function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob(["\uFEFF"+csv], {type: "text/csv; charset=utf-8"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

//Exporte le tableau en fichier CSV
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#tableInterv tr");
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        if (cols.length!==0){
            for (var j = 0; j < cols.length; j++) {
                row.push(cols[j].innerText);
            }
            csv.push(row.join(";"));
        }
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}


//JSPDF

const doc = new jsPDF({
    orientation: "l", //set orientation
    unit: "pt", //set unit for document
    format: [840, 1188] //set document standard
});

laTableStyle = {
    lineColor: 100, 
    lineWidth: 1,
    fillColor : [255, 255, 255],
    textColor : [0,0,0],
    sizes:12,
    fontSize: 10,
    halign:'center',
    valign:'middle'
};

//Exporte le tableau en fichier PDF 
function exportToPDF(limitDatas, pdfInfosElu, pdfListCadre, AM1list, AM2list){
    const name = "Rapports";

    /* LES INFOS AVANT LE ABLEAU */
    doc.setFontSize(12);
    doc.setFontType('bold');
    doc.text(40, 42, 'Mairie de Fleury-les-Aubrais');
    doc.setFontType('normal')
    doc.text(40, 58, 'Direction Parimoine Bâti');
    doc.setFontSize(18);
    doc.setFontType('bold');
    doc.text(270, 91, 'COMPTE RENDU ET FICHE D\'INTERVENTION - ASTREINTE TECHNIQUE');
    doc.rect(10, 70, 1168, 28);

    doc.setFontSize(12);
    doc.text(40,125,limitDatas);

    doc.setFontType('bold');
    doc.text(40,155,"Elu : ");
    doc.setFontType('normal');
    doc.text(100,155,pdfInfosElu);

    doc.setFontType('bold');
    doc.text(40,175,"Cadre : ");
    doc.setFontType('normal');

    var arrayCadre = pdfListCadre.split(',');
    i = 0
    arrayCadre.forEach(element => {
        doc.text(100,175+(i*20), element);
        i = i+1;
    });


    var arrayAM1 = AM1list.split(',');
    doc.setFontType('bold');
    doc.text(40,175+(i*20), "AM1 :");

    arrayAM1.forEach(element => {
        doc.setFontType('normal');
        doc.text(100,175+(i*20), element);
        i = i+1;
    });

    var arrayAM2 = AM2list.split(',');
    doc.setFontType('bold');
    doc.text(40,175+(i*20), "AM2 :");

    arrayAM2.forEach(element => {
        doc.setFontType('normal');
        doc.text(100,175+(i*20), element);
        i = i+1;
    });

    doc.setFillColor(235,235,235);
    doc.rect(590,105+(i*20), 335, 45, 'F');
    doc.text(605,125+(i*20), "La première intervention de la semaine est comptée 1h\nEt pour les suivantes, le temps est compté au 1/4 d'heure");

    /* LE PREMIER NIVEAU D'HEADER POUR LA TABLE */

    doc.setFontSize(12);
    doc.setFontType('bold');
    doc.setLineWidth(1);
    doc.setDrawColor(105,105,105);

    doc.setFillColor(245,245,245);
    doc.rect(40, 180+(i*20), 480, 50, 'FD');
    doc.text(235,210+(i*20), "Appel");

    doc.setFillColor(245,245,245);
    doc.rect(520, 180+(i*20), 480, 50, 'FD');
    doc.text(720,197+(i*20), "Intervention");

    doc.setFillColor(235,235,235);
    doc.rect(520, 205+(i*20), 120, 25, 'FD');
    doc.text(560,222+(i*20), "Cadre");

    doc.setFillColor(235,235,235);
    doc.rect(640, 205+(i*20), 120, 25, 'FD');
    doc.text(685,222+(i*20), "AM1");

    doc.setFillColor(235,235,235);
    doc.rect(760, 205+(i*20), 120, 25, 'FD');
    doc.text(805,222+(i*20), "AM2");

    /* LA TABLE AVEC LES DONNEES */
    doc.autoTable(columns, rows, {
        styles: laTableStyle,
        columnStyles: {
            date: {columnWidth:60},
            heure: {overflow: 'linebreak', columnWidth:40},
            demandeur: {overflow: 'linebreak',columnWidth:110},
            lieu: {overflow: 'linebreak',columnWidth:110},
            motif: {overflow: 'linebreak',columnWidth:160},
            
            from: {columnWidth:40},
            to: {columnWidth:40},
            duree: {columnWidth:40},
            
            from2: {columnWidth:40},
            to2: {columnWidth:40},
            duree2: {columnWidth:40},
            from3: {columnWidth:40},
            to3: {columnWidth:40},
            duree3: {columnWidth:40},

            intervention_sur: {overflow: 'linebreak',columnWidth:120},
            observations: {overflow: 'linebreak',columnWidth:140}
        },
        alternateRowStyles: {
            fillColor : [255, 255, 255]
        },
        margin:{top: 50},
        startY: 190+(i*20)+40
    });
    
    /* TABLE 'TEMPS A' */
    doc.setDrawColor(105,105,105);
    doc.setLineWidth(1);
    doc.setFontSize(12);
    doc.setFontType('bold');

    doc.rect(360, doc.autoTable.previous.finalY+21, 160, 44);
    doc.text(410,doc.autoTable.previous.finalY+40, "TEMPS À :");
    doc.setFontSize(10);
    doc.setFontType('normal');
    doc.text(388,doc.autoTable.previous.finalY+55, "(cocher la bonne case)");

    doc.autoTable(columns2, rows2, {
        styles: laTableStyle,
        columnStyles: {
            from: {columnWidth:80, fillColor : [255, 255, 255],},
            to: {columnWidth:40, fillColor : [255, 255, 255],},
            from2: {columnWidth:80, fillColor : [255, 255, 255],},
            to2: {columnWidth:40, fillColor : [255, 255, 255],},
            from3: {columnWidth:80, fillColor : [255, 255, 255],},
            to3: {columnWidth:40, fillColor : [255, 255, 255],}
        },
        alternateRowStyles: {
            fillColor : [255, 255, 255]
        },
        margin:{left: 520},
        startY: doc.autoTable.previous.finalY,
    });


    /* LES CASES VISA */

    doc.setDrawColor(105,105,105);
    doc.setLineWidth(1);
    doc.setFontSize(12);
    doc.setFontType('bold');
    doc.rect(360, doc.autoTable.previous.finalY, 160, 40);
    doc.text(425,doc.autoTable.previous.finalY+25, "VISA");
    
    doc.rect(640, doc.autoTable.previous.finalY, 120, 40);
    doc.rect(760, doc.autoTable.previous.finalY, 120, 40);

    doc.rect(900, doc.autoTable.previous.finalY, 50, 40);
    doc.text(910,doc.autoTable.previous.finalY+25, "VISA");
    doc.rect(950, doc.autoTable.previous.finalY, 190, 40);

    doc.save(`${name}.pdf`);
};


/*Things to keep for later
 doc.addImage(url, type, x, y, w, h);
 const lines = doc.splitTextToSize(<insert string>, 5, {
   fontSize: 10,
   fontStyle: "normal",
   fontName: "helvetica"
 });
console.log(doc.getFontList()) //logs a list of fonts available


const btn = document.querySelector("button");
const input = document.querySelector("input");
const sizes = {
    xs: 10, 
    sm : 10, 
    p: 10, 
    h3: 18, 
    h2: 20, 
    h1: 22
};

const fonts = {
    times: 'Times', 
    helvetica: 'Helvetica'
};
const margin = 0.5;
const verticalOffset = margin;


var rows = [
    {
    "date": status, 
    "heure": `${data1}\n${data2}`, 
    "col3": creator, 
    "col4": date.getUTCDate(),
    },
    {
    "col1": "data-cell_r2_c1", 
    "col2": "data-cell_r2_c2", 
    "col3": "data-cell3_r2_c3", 
    "col4": "data-cell4_r2_c4"
    },
    {
    "col1": "data-cell_r3_c1", 
    "col2": "data-cell_r3_c2", 
    "col3": "data-cell3_r3_c3", 
    "col4": "105"
    }
];*/