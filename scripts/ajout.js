document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    function getSelectedText(selectName) {
        const select = document.getElementsByName(selectName)[0];
        if (!select) return null;
        return select.options[select.selectedIndex].text;
    }

    const dataToSend = {
        Iddoc: "10",
        Nb_panneaux: formData.get('nombre_panneaux'),
        Nb_onduleurs: formData.get('nombre_onduleurs'),
        Puissance_crete: formData.get('puissance_crete'),
        Pente: formData.get('pente'),
        Orientation: formData.get('orientation'),
        Surface: formData.get('surface'),
        Production_pvgis: formData.get('production_pvgis'),
        Pente_optimum: formData.get('pente_optimum'),
        Orientation_opti: formData.get('orientation_optimum'),
        Mois_installation: getMois(formData.get('date_installation')),
        An_installation: getAnnee(formData.get('date_installation')),
        id_installateur: formData.get('installateur'),

        id_modele_panneau: formData.get('modele_du_panneau'),
        id_marque_panneau: formData.get('marque_du_panneaux'),
        Paneeaux_modele: getSelectedText('modele_du_panneau'),
        Panneaux_marque: getSelectedText('marque_du_panneaux'),

        id_modele_onduleur: formData.get('modele_du_onduleur'),
        id_marque_onduleur: formData.get('marque_du_onduleur'),
        Onduleur_modele: getSelectedText('modele_du_onduleur'),
        Onduleur_marque: getSelectedText('marque_du_onduleur'),

        Country: formData.get('pays'),
        Reg_nom: formData.get('region'),
        Dep_nom: formData.get('departement'),
        Nom_standard: formData.get('ville'),
        Code_postal: formData.get('code_postal'),
        Lat: formData.get('latitude'),
        Lon: formData.get('longitude'),
        Installeur: formData.get('installateur'),
    };

    fetch('http://localhost/projetweb_groupe5/api/solar_manage/api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dataToSend)
    })
        .then(resp => resp.json())
        .then(data => console.log('Réponse API:', data))
        .catch(err => console.error('Erreur API:', err));
});

function getMois(dateStr) {
    if (!dateStr) return null;
    const d = new Date(dateStr);
    return d.getMonth() + 1;
}
function getAnnee(dateStr) {
    if (!dateStr) return null;
    const d = new Date(dateStr);
    return d.getFullYear();
}
