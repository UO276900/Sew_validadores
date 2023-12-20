import xml.etree.ElementTree as ET

def main():
    tree = ET.parse('xml/rutasEsquema.xml')
    root = tree.getroot()
    rutas_ns = "{http://tempuri.org/rutas}"
    count = 1
    for ruta in root.findall(f".//{rutas_ns}ruta"):
        svg = ['<svg xmlns="http://www.w3.org/2000/svg">\n', ' <polyline points="']
        xInicial = 50
        yInicial = 500-10
        svg.append(f"{xInicial},{yInicial} ")
        yMax = 0.0
        
        xAcum = xInicial

        if (count == 1):
            yMax = 0.0
            for hito in ruta.findall(f".//{rutas_ns}hito"):
                coords = hito.find(f".//{rutas_ns}coordenadas")
                alt = coords.find(f"{rutas_ns}altitud").text
                if (float(alt)>yMax):
                    yMax=float(alt)                     
            y2 = yMax+10
            y2 = 500-y2*2
            svg.append(f"{xInicial},{y2} ")
            for hito in ruta.findall(f".//{rutas_ns}hito"):
                coords = hito.find(f".//{rutas_ns}coordenadas")
                alt = coords.find(f"{rutas_ns}altitud").text
                dAnterior = hito.find(f".//{rutas_ns}distancia_anterior").attrib["unidades"]
                xHito = xAcum + float(dAnterior)
                xAcum = xHito
                yHito = 500-float(alt) * 2
                svg.append(f"{xHito},{yHito} ")
        if (count == 2):
            yMax = 0.0
            for hito in ruta.findall(f".//{rutas_ns}hito"):
                coords = hito.find(f".//{rutas_ns}coordenadas")
                alt = coords.find(f"{rutas_ns}altitud").text
                if (float(alt)>yMax):
                    yMax=float(alt)                     
            y2 = yMax+10
            y2 = 500-y2/10
            svg.append(f"{xInicial},{y2} ")
            for hito in ruta.findall(f".//{rutas_ns}hito"):
                coords = hito.find(f".//{rutas_ns}coordenadas")
                alt = coords.find(f"{rutas_ns}altitud").text
                dAnterior = hito.find(f".//{rutas_ns}distancia_anterior").attrib["unidades"]
                xHito = xAcum + (float(dAnterior) * 10)
                xAcum = xHito
                yHito = 500-float(alt)/10
                svg.append(f"{xHito},{yHito} ")
        if (count == 3):
            yMax = 0.0
            for hito in ruta.findall(f".//{rutas_ns}hito"):
                coords = hito.find(f".//{rutas_ns}coordenadas")
                alt = coords.find(f"{rutas_ns}altitud").text
                if (float(alt)>yMax):
                    yMax=float(alt)                     
            y2 = yMax+10
            y2 = 500-y2/10
            svg.append(f"{xInicial},{y2} ")
            for hito in ruta.findall(f".//{rutas_ns}hito"):
                coords = hito.find(f".//{rutas_ns}coordenadas")
                alt = coords.find(f"{rutas_ns}altitud").text
                dAnterior = hito.find(f".//{rutas_ns}distancia_anterior").attrib["unidades"]
                xHito = xAcum + float(dAnterior)
                xAcum = xHito
                yHito = 500-float(alt) / 10
                svg.append(f"{xHito},{yHito} ")
        
        svg.append(f"{xAcum},{yInicial} ")
        svg.append(f"{xInicial},{yInicial}")
        svg.append('" stroke="black" fill="none"/>\n')
        svg.append('</svg>')

        with open(f"xml/perfil{count}.svg","w") as f:
            f.writelines(svg)
        count = count + 1

            
    

if __name__ == "__main__":
    main()