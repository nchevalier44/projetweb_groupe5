import csv
import sys

class SQLGenerator:
    def __init__(self):
        self.pays_cache = {}
        self.regions_cache = {}
        self.departements_cache = {}
        
        # Compteurs pour les IDs auto-increment
        self.pays_id = 1
        self.region_id = 1
        self.departement_id = 1
        
    def generate_sql_from_csv(self, csv_file_path, output_file_path=None):
        """
        Génère les requêtes SQL d'insertion à partir du fichier CSV
        """
        sql_statements = []
        
        # En-têtes SQL
        sql_statements.append("-- Requêtes d'insertion générées automatiquement")
        sql_statements.append("-- Base de données: solar_manager")
        sql_statements.append("")
        sql_statements.append("USE solar_manager;")
        sql_statements.append("")
        sql_statements.append("-- Désactiver les vérifications de clés étrangères temporairement")
        sql_statements.append("SET FOREIGN_KEY_CHECKS = 0;")
        sql_statements.append("")
        ville_codeinsee_map = {}
        try:
            with open(csv_file_path, 'r', encoding='utf-8') as csvfile:
                reader = csv.DictReader(csvfile)
                
                for row in reader:
                    # Nettoyer les données
                    code_insee = row['code_insee']
                    nom_standard = row['nom_standard'].replace("'", "''")  # Échapper les apostrophes
                    reg_code = row['reg_code']
                    reg_nom = row['reg_nom'].replace("'", "''")
                    dep_code = row['dep_code']
                    dep_nom = row['dep_nom'].replace("'", "''")
                    code_postal = row['code_postal']
                    population = int(row['population'])
                    
                    # Gérer le pays (France par défaut)
                    pays_id = self._handle_pays("France")
                    
                    # Gérer la région
                    region_id = self._handle_region(reg_nom, reg_code, pays_id)
                    
                    # Gérer le département
                    departement_id = self._handle_departement(dep_nom, dep_code, region_id)
                    
                    # Générer l'insertion pour la ville
                    ville_sql = f"INSERT INTO ville (code_insee, Nom_standard, Population, Code_postal, id) VALUES ('{code_insee}', '{nom_standard}', {population}, {code_postal}, {departement_id});"
                    sql_statements.append(ville_sql)
                    ville_codeinsee_map[nom_standard] = code_insee
        
        except FileNotFoundError:
            print(f"Erreur: Le fichier {csv_file_path} n'a pas été trouvé.")
            return
        except Exception as e:
            print(f"Erreur lors du traitement du CSV: {e}")
            return
        
        # Ajouter les insertions pour pays, régions et départements au début
        insertions_preliminaires = self._generate_preliminary_insertions()
        
        # Réactiver les vérifications de clés étrangères
        sql_statements.append("")
        sql_statements.append("-- Réactiver les vérifications de clés étrangères")
        sql_statements.append("SET FOREIGN_KEY_CHECKS = 1;")
        
        # Combiner toutes les requêtes
        final_sql = insertions_preliminaires + sql_statements
        
        # Écrire dans un fichier ou afficher
        if output_file_path:
            try:
                with open(output_file_path, 'w', encoding='utf-8') as output_file:
                    output_file.write('\n'.join(final_sql))
                print(f"Requêtes SQL générées dans le fichier: {output_file_path}")
            except Exception as e:
                print(f"Erreur lors de l'écriture du fichier: {e}")
        else:
            # Afficher les premières lignes comme exemple
            print("Aperçu des requêtes SQL générées:")
            print("=" * 50)
            for i, line in enumerate(final_sql[:20]):  # Afficher les 20 premières lignes
                print(line)
            if len(final_sql) > 20:
                print(f"... et {len(final_sql) - 20} lignes supplémentaires")
            
            print("\n" + "=" * 50)
            print(f"Total: {len([s for s in final_sql if s.startswith('INSERT')])} requêtes INSERT générées")

        with open("villecodeinseemap.txt", 'w', encoding='utf-8') as f:
            for k in ville_codeinsee_map:
                f.write(f"{k}={ville_codeinsee_map[k]}\n")
    
    def _handle_pays(self, pays_nom):
        """Gère l'insertion du pays et retourne son ID"""
        if pays_nom not in self.pays_cache:
            self.pays_cache[pays_nom] = self.pays_id
            self.pays_id += 1
        return self.pays_cache[pays_nom]
    
    def _handle_region(self, reg_nom, reg_code, pays_id):
        """Gère l'insertion de la région et retourne son ID"""
        region_key = (reg_nom, reg_code)
        if region_key not in self.regions_cache:
            self.regions_cache[region_key] = {
                'id': self.region_id,
                'pays_id': pays_id
            }
            self.region_id += 1
        return self.regions_cache[region_key]['id']
    
    def _handle_departement(self, dep_nom, dep_code, region_id):
        """Gère l'insertion du département et retourne son ID"""
        dep_key = (dep_nom, dep_code)
        if dep_key not in self.departements_cache:
            self.departements_cache[dep_key] = {
                'id': self.departement_id,
                'region_id': region_id
            }
            self.departement_id += 1
        return self.departements_cache[dep_key]['id']
    
    def _generate_preliminary_insertions(self):
        """Génère les insertions pour pays, régions et départements"""
        sql_statements = []
        
        # Insertions pour les pays
        sql_statements.append("-- Insertion des pays")
        for pays_nom, pays_id in self.pays_cache.items():
            sql_statements.append(f"INSERT INTO pays (id, Country) VALUES ({pays_id}, '{pays_nom}');")
        
        sql_statements.append("")
        
        # Insertions pour les régions
        sql_statements.append("-- Insertion des régions")
        for (reg_nom, reg_code), data in self.regions_cache.items():
            sql_statements.append(f"INSERT INTO region (id, Reg_nom, Reg_code, id_pays) VALUES ({data['id']}, '{reg_nom}', {reg_code}, {data['pays_id']});")
        
        sql_statements.append("")
        
        # Insertions pour les départements
        sql_statements.append("-- Insertion des départements")
        for (dep_nom, dep_code), data in self.departements_cache.items():
            sql_statements.append(f"INSERT INTO departement (id, Dep_nom, Dep_code, id_region) VALUES ({data['id']}, '{dep_nom}', {dep_code}, {data['region_id']});")
        
        sql_statements.append("")
        sql_statements.append("-- Insertion des villes")
        
        return sql_statements

def main():
    generator = SQLGenerator()
    
    # Chemins des fichiers
    csv_file = "communes-france-2024-limite.csv"  # Remplacez par le chemin de votre fichier CSV
    output_file = "insertions_solar_manager.sql"  # Fichier de sortie
    
    print("Génération des requêtes SQL pour la base solar_manager...")
    print(f"Lecture du fichier CSV: {csv_file}")
    
    # Générer les requêtes SQL
    generator.generate_sql_from_csv(csv_file, output_file)

if __name__ == "__main__":
    main()