import csv
import sys


def generate_sql_insertions(csv_file, output_file):
    valeurs_vues = set()
    i = 1

    marquepcache = {}
    modelepcache = {}
    marqueocache = {}
    modeleocache = {}
    loccache = {}
    ville_to_insee = {}
    onduleurcache = {}
    panneaucache = {}
    installateurcache = {}

    with open('villecodeinseemap.txt', 'r', encoding='utf-8') as f:
        for line in f:
            if '=' in line:
                nom, code = line.strip().split('=', 1)
                ville_to_insee[nom.strip()] = code.strip()


    with open(output_file, 'w', encoding='utf-8') as f:
        with open(csv_file, newline='', encoding='utf-8') as csvfile:
            reader = csv.DictReader(csvfile)
            rows = list(reader)

        # Marques panneaux
        f.write("-- Marques des panneaux extraites du CSV\n\n")
        for row in rows:
            marquep = row['panneaux_marque'].strip()
            if marquep not in valeurs_vues:
                valeurs_vues.add(marquep)
                f.write(f'INSERT INTO marque_panneau (id, Panneaux_marque) VALUES ({i}, "{marquep}");\n')
                marquepcache[marquep] = i
                i += 1

        # Modèles panneaux
        valeurs_vues.clear()
        f.write("\n-- Modèles des panneaux extraits du CSV\n\n")
        i = 1
        for row in rows:
            modelp = row['panneaux_modele'].strip()
            if modelp not in valeurs_vues:
                valeurs_vues.add(modelp)
                f.write(f'INSERT INTO modele_panneau (id, Panneaux_modele) VALUES ({i}, "{modelp}");\n')
                modelepcache[modelp] = i
                i += 1

        # Marques onduleurs
        valeurs_vues.clear()
        f.write("\n-- Marques des onduleurs extraites du CSV\n\n")
        i = 1
        for row in rows:
            marqueo = row['onduleur_marque'].strip()
            if marqueo not in valeurs_vues:
                valeurs_vues.add(marqueo)
                f.write(f'INSERT INTO marque_onduleur (id, Onduleur_marque) VALUES ({i}, "{marqueo}");\n')
                marqueocache[marqueo] = i
                i += 1

        # Modèles onduleurs
        valeurs_vues.clear()
        f.write("\n-- Modèles des onduleurs extraits du CSV\n\n")
        i = 1
        for row in rows:
            modeleo = row['onduleur_modele'].strip()
            if modeleo not in valeurs_vues:
                valeurs_vues.add(modeleo)
                f.write(f'INSERT INTO modele_onduleur (id, Onduleur_modele) VALUES ({i}, "{modeleo}");\n')
                modeleocache[modeleo] = i
                i += 1

        # Installateurs
        valeurs_vues.clear()
        f.write("\n-- Installateurs extraits du CSV\n\n")
        i = 1
        for row in rows:
            installateur = row['installateur'].strip()
            if installateur not in valeurs_vues:
                valeurs_vues.add(installateur)
                installateurcache[installateur] = i
                f.write(f'INSERT INTO installateur (id, Installateur) VALUES ({i}, "{installateur}");\n')
                i += 1

        # Panneaux
        valeurs_vues.clear()
        f.write("\n-- Panneaux (liés aux marques et modèles)\n\n")
        i = 1
        for row in rows:
            marquep = row['panneaux_marque'].strip()
            modelp = row['panneaux_modele'].strip()
            cle = (marquep, modelp)
            if cle not in valeurs_vues:
                valeurs_vues.add(cle)
                id_marque = marquepcache[marquep]
                id_modele = modelepcache[modelp]
                panneaucache[cle] = i
                f.write(f'INSERT INTO panneau (id, id_marque_panneau, id_modele_panneau) VALUES ({i}, {id_marque}, {id_modele});\n')
                i += 1

        # Onduleurs
        valeurs_vues.clear()
        f.write("\n-- Onduleurs (liés aux marques et modèles)\n\n")
        i = 1
        for row in rows:
            marqueo = row['onduleur_marque'].strip()
            modeleo = row['onduleur_modele'].strip()
            cle = (marqueo, modeleo)
            if cle not in valeurs_vues:
                valeurs_vues.add(cle)
                id_marque = marqueocache[marqueo]
                id_modele = modeleocache[modeleo]
                onduleurcache[cle] = i
                f.write(f'INSERT INTO onduleur (id, id_marque_onduleur, id_modele_onduleur) VALUES ({i}, {id_marque}, {id_modele});\n')
                i += 1
        
        #Localisation 
        valeurs_vues.clear()
        f.write("\n-- Localisations --\n\n")
        i = 1
        for row in rows:
            lat = row['lat'].strip()
            lon = row['lon'].strip()
            coord = (lat,lon)
            ville = row['locality'].strip()
            codeInsee = ville_to_insee.get(ville)
            if coord not in valeurs_vues :
                valeurs_vues.add(coord)
                f.write(f'INSERT INTO localisation (id, Lat, Lon, code_insee) VALUES ({i}, {lat}, {lon}, "{codeInsee}");\n')
                loccache[coord] = i
                i += 1
        
        #Installation
        valeurs_vues.clear()
        f.write("\n-- Installations\n\n")
        i = 1
        for row in rows:
            iddoc = row['iddoc'].strip()
            mois_installation = row['mois_installation'].strip()
            an_installation = row['an_installation'].strip()
            nb_panneaux = row['nb_panneaux'].strip()
            nb_onduleur = row['nb_onduleur'].strip()
            panneaux_marque = row['panneaux_marque'].strip()
            panneaux_modele = row['panneaux_modele'].strip()
            onduleur_marque = row['onduleur_marque'].strip()
            onduleur_modele = row['onduleur_modele'].strip()
            puissance_crete = row['puissance_crete'].strip()
            surface = row['surface'].strip()
            pente = row['pente'].strip()
            pente_opti = row['pente_optimum'].strip()
            orientation = row['orientation'].strip()
            orientation_opti = row['orientation_optimum'].strip()
            installateur = row['installateur'].strip()
            production_pvgis = row['production_pvgis'].strip()
            lat = str(float(row['lat']))
            lon = str(float(row['lon']))

            onduleur = (onduleur_marque, onduleur_modele)
            panneau = (panneaux_marque, panneaux_modele)
            coord = (lat, lon)

            id_onduleur = onduleurcache[onduleur]
            id_panneau = panneaucache[panneau]
            id_localisation = loccache[coord]
            id_installateur = installateurcache.get(installateur, 'NULL')

            # Remplacer vide par NULL (sinon garder la valeur brute)
            pente_optimum = 'NULL' if pente_opti == '' else pente_opti
            orientation_optimum = 'NULL' if orientation_opti == '' else orientation_opti

            f.write(
                f"INSERT INTO installation (id, Iddoc, Nb_panneaux, Nb_onduleurs, Puissance_crete, Pente, "
                f"Orientation, Surface, Production_pvgis, Pente_optimum, Orientation_opti, "
                f"Mois_installation, An_installation, id_installateur, id_onduleur, id_panneau, id_localisation) "
                f"VALUES ({i}, {iddoc}, {nb_panneaux}, {nb_onduleur}, {puissance_crete}, {pente}, "
                f"'{orientation}', {surface}, {production_pvgis}, {pente_optimum}, {orientation_optimum}, "
                f"{mois_installation}, {an_installation}, {id_installateur}, {id_onduleur}, {id_panneau}, {id_localisation});\n"
            )
            i += 1









        
            

        







generate_sql_insertions("data.csv","instal.sql")