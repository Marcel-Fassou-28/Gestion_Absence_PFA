import cv2
import pytesseract
import pandas as pd
from PIL import Image

# Charger et prétraiter l'image
img = cv2.imread("table_image.jpg")
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)[1]

# Extraire le texte avec Tesseract
config = '--psm 6'
text = pytesseract.image_to_string(thresh, lang='fra', config=config)

# Post-traitement pour structurer en table
lines = text.split('\n')
data = [line.split() for line in lines if line.strip()]
df = pd.DataFrame(data)
print(df)

# Optionnel : Sauvegarder en CSV
# #df.to_csv("tableau.csv", index=False)

# from img2table.document import Image
# from img2table.ocr import TesseractOCR

# # Charger l'image
# img = Image(src="table_image.jp")

# # Initialiser l'OCR
# ocr = TesseractOCR(n_threads=1, lang="fra")  # 'fra' pour français

# # Extraire les tables
# tables = img.extract_tables(ocr=ocr, borderless_tables=True)

# # Afficher les résultats
# for table in tables:
#     print(table.content)  # Structure de la table
#     df = table.df  # Convertir en DataFrame Pandas
#     print(df)

# # Optionnel : Exporter vers Excel
# img.to_xlsx("tables.xlsx", ocr=ocr)