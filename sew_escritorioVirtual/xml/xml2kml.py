import xml.etree.ElementTree as ET

def main():
    tree = ET.parse('xml/rutasEsquema.xml')
    root = tree.getroot()
    rutas_ns = "{http://tempuri.org/rutas}"

    count = 1

    for ruta in root.findall(f".//{rutas_ns}ruta"):
        nombre = ruta.find(f"{rutas_ns}nombre").text

        kml = ET.Element('kml', xmlns="http://www.opengis.net/kml/2.2")
        doc = ET.SubElement(kml, 'Document')

        # Agrega marcador para la coordenada de inicio
        inicio = ruta.find(f".//{rutas_ns}inicio")
        inicio_coords = inicio.find(f".//{rutas_ns}coordenadas")
        inicio_lat = inicio_coords.find(f"{rutas_ns}latitud").text
        inicio_long = inicio_coords.find(f"{rutas_ns}longitud").text
        inicio_alt = inicio_coords.find(f"{rutas_ns}altitud").text

        inicio_placemark = ET.SubElement(doc, 'Placemark')
        inicio_name = ET.SubElement(inicio_placemark, 'name')
        inicio_name.text = f"Inicio - {nombre}"

        inicio_point = ET.SubElement(inicio_placemark, 'Point')
        inicio_coordinates = ET.SubElement(inicio_point, 'coordinates')
        inicio_coordinates.text = f"{inicio_lat},{inicio_long},{inicio_alt}"

        for hito in ruta.findall(f".//{rutas_ns}hito"):
            coords = hito.find(f".//{rutas_ns}coordenadas")
            lat = coords.find(f"{rutas_ns}latitud").text
            long = coords.find(f"{rutas_ns}longitud").text
            alt = coords.find(f"{rutas_ns}altitud").text

            placemark = ET.SubElement(doc, 'Placemark')
            name = ET.SubElement(placemark, 'name')
            name.text = f"{nombre} - {hito.find(f'.//{rutas_ns}nombre').text}"

            point = ET.SubElement(placemark, 'Point')
            coordinates = ET.SubElement(point, 'coordinates')
            coordinates.text = f"{lat},{long},{alt}"

        tree = ET.ElementTree(kml)
        tree.write(f"xml/ruta{count}.kml")
        count += 1

if __name__ == "__main__":
    main()
