from PIL import Image, ImageDraw, ImageFont
import os

# Paramètres de l'image
width, height = 800, 600
background_color = (255, 255, 255)  # Blanc
text_color = (0, 0, 0)  # Noir
line_color = (0, 0, 0)  # Noir pour les bordures

# Créer une image vide
image = Image.new("RGB", (width, height), background_color)
draw = ImageDraw.Draw(image)

# Charger une police (utiliser une police par défaut ou spécifier un chemin)
try:
    font = ImageFont.truetype("arial.ttf", 20)
except:
    font = ImageFont.load_default()

# Données de la table
headers = ["Numéro", "Nom", "Prénom", "Date", "Classe"]
data = [
    ["1", "Dupont", "Jean", "2025-04-21", "A1"],
    ["2", "Martin", "Sophie", "2025-04-21", "A2"],
    ["3", "Lefèvre", "Paul", "2025-04-21", "B1"],
]

# Paramètres de la table
start_x, start_y = 50, 50
cell_width = 140
cell_height = 40
header_height = 50

# Dessiner les en-têtes
for col, header in enumerate(headers):
    x = start_x + col * cell_width
    draw.rectangle(
        (x, start_y, x + cell_width, start_y + header_height),
        outline=line_color,
        width=2
    )
    draw.text(
        (x + 10, start_y + 10),
        header,
        fill=text_color,
        font=font
    )

# Dessiner les lignes de données
for row_idx, row in enumerate(data):
    for col_idx, cell in enumerate(row):
        x = start_x + col_idx * cell_width
        y = start_y + header_height + row_idx * cell_height
        draw.rectangle(
            (x, y, x + cell_width, y + cell_height),
            outline=line_color,
            width=2
        )
        draw.text(
            (x + 10, y + 10),
            cell,
            fill=text_color,
            font=font
        )

# Sauvegarder l'image
output_path = "table_image.jpg"
image.save(output_path, "JPEG", quality=95)
print(f"Image sauvegardée à : {os.path.abspath(output_path)}")