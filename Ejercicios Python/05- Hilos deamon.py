# Importacion Librerias
import threading
import time 

#Funcion que ejecute un Hilo daemon 
def hilo_daemon():
    
    while True:
        print("Hilo Daemon está es ejecución...")
        time.sleep(1)
        
        #Crear un Hilo Daemon
        daemon = threading.Thread(target=hilo_daemon, daemon=True)
        
        #Inicializar el Hilo
        daemon.start()
        
        #Código Principal
        time.sleep(10)
        print("Programa finalizado")
#Recordatorio:
#Los Hilos Daemon se ejecutan en segunda plano y se detiene automáticamente cuando el Hilo del programa principal termina, sin importar si han 
#completado su tarea o no.
#los Hilos Deamon son útiles para tareas no criticas, como monitoreo o registro, ya que no bloquean el cierre del programa
#y pueden ser interrumpidos sin afectar el flujo principal.