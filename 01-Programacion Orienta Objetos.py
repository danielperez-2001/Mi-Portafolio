# Clase Portafolio
class PortafolioInversiones:

    """
    Clase que gestiona las inversiones de un portafolio.
    """

    def __init__(self):
        """
        Inicializa un portafolio con una lista vacía de inversiones.
        """
        self.inversiones = []

    def agregar_inversion(self, nombre_activo, capital, rentabilidad_esperada):
        """
        Agregar una nueva inversión al portafolio.

        Parámetros:
            - nombre_activo: Nombre del Instrumento Financiero (por ejemplo: AAPL, BTC).
            - capital: Cantidad de capital invertido.
            - rentabilidad_esperada: Rentabilidad esperada en porcentaje (por ejemplo, 5%).
        """

        # Agregar inversión al portafolio
        inversion = {
            "activo": nombre_activo,
            "capital": capital,
            "rentabilidad": rentabilidad_esperada
        }

        self.inversiones.append(inversion)

        print(
            f"Inversión en {nombre_activo} agregada: "
            f"${capital} con una rentabilidad esperada de {rentabilidad_esperada}%"
        )

    def calcular_rentabilidad_total(self):
        """
        Calcula la rentabilidad esperada total del portafolio
        sumando la rentabilidad de cada inversión.
        """

        # Calcular beneficios
        rentabilidad_total = 0

        for inversion in self.inversiones:
            # Rentabilidad para cada inversión:
            # capital * (rentabilidad / 100)
            rentabilidad_total += (
                inversion["capital"] * inversion["rentabilidad"] / 100
            )

        print(
            f"Rentabilidad total esperada del portafolio: "
            f"${rentabilidad_total:.2f}"
        )
        
        return rentabilidad_total
    
    def mostrar_inversiones(self):
        """
        Muestra todas las inversiones del portafolio.
        """
        
        if len(self.inversiones) == 0:
            print("No hay inversiones en el portafolio")
        else:
            print("Portafolio de inversiones:")
            for inversion in self.inversiones:
                print(f"Activo: {inversion['activo']}, Capital Invertido: {inversion['capital']},Rentabilidad: {inversion['rentabilidad']}%")
                
                #Ejemplo de uso del Portafolio de Inversiones
                
                #Crear una instancia
                mi_portafolio = PortafolioInversiones()
                
            #Agregar algunas inversiones 
                mi_portafolio.agregar_inversion(nombre_activo= 'AAPL', capital = 10_000, rentabilidad_esperada = 8)
                mi_portafolio.agregar_inversion(nombre_activo= 'GOOGL', capital = 5_000, rentabilidad_esperada = 10)
                mi_portafolio.agregar_inversion(nombre_activo= 'BTC', capital = 2_000, rentabilidad_esperada = 15)
                
                #Mostara todas las inversiones 
                mi_portafolio.mostrar_inversiones()
                
                #calcular la rentabilidad esperada del portafolio 
                mi_portafolio.calcular_rentabilidad_total()
                
                
                #recordatorio
                #las clases permiten organizar datos (atributos) y comportamientos (metodos ) en una estructura coherente. esto facilita la 
                #encapsulacion, ta que los atributos se pueden proteger y los metodos pueden operar directamente sobre esos datos.
                #-las clases permitenb crear multiples instancias a partir de la misma platilla, cada una con sus propios valores y estados.
                #Esto promueve la reutilizacion del codig, ya que todos los objetos comparten la misma estructura y funcionalidad.