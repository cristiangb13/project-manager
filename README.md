<h1 align="center">
Project Manager
</h1>

## Setup

### Instalar dependencias
Instalar todas las dependencias del proyecto:
- `make setup`

### Tests
Para comprobar que todo está correctamente configurado, ejecutar los tests:
-  `make test`

### Iniciar aplicación
1. `make up`
2. La aplicación tiene disponible un endpoint que devuelve los proyectos más rentables utilizando los datos de prueba
proporcionados:
    1. GET http://localhost:8000/projects

## Proceso de desarrollo
Para realizar la prueba he creado una pequeña aplicación con un endpoint que devuelve los proyectos más rentables
utilizando los datos de prueba proporcionados.

Aunque para este caso no era necesario, y puede que sea un poco overkill, he optado por seguir una arquitectura
hexagonal y aplicar alguna cosa de DDD más que nada para que veáis como suelo hacer las cosas.

Básicamente he creado el controlador, que utiliza el use case, que a su vez utiliza el servicio de dominio. El servicio
de dominio tiene inyectado el repositorio para obtener todos los proyectos. En este caso la implementación es inmemory
y cargo todos los proyectos del ejemplo. Pero se podría crear cualquier implementación, por ejemplo con doctrine y
obtener los proyectos de base de datos.

El repositorio devuelve una ProjectCollection, que básicamente es un array de projects, al crear la collection evitamos
ir pasando arrays por el código y podemos tipar mejor. Además esto nos permite encapsular la lógica del negocio
relacionada con un grupo de proyectos en ProjectCollection.

### Algoritmo
Aunque la algoritmia no diría que es mi punto fuerte he intentado dejar el código lo más claro posible. Aun así voy a
intentar explicar el proceso que he seguido.

Para poder saber los proyectos con mejor rentabilidad mi approach ha sido obtener todas las combinaciones posibles de
proyectos que se pueden realizar. Por ejemplo, con los datos de ejemplo, estás son las posibles combinaciones:
- Molina, Mijas (32000)
- Tenerife, Arturo (26000)
- Tenerife, Mijas (25000)

Sumando la rentabilidad de cada combinación vemos que Molina y Mijas es la más rentable.

Entonces teniendo estos datos de ejemplo:

| Nombre   | Fecha Inicio | Fecha Final | Rentabilidad |
| ---------|:------------:| :--------:  | -----------: |
| Alfa     | 01/01/2022   | 04/01/2022  | 1000         |
| Beta     | 02/01/2022   | 05/01/2022  | 2000         |
| Charlie  | 05/01/2022   | 10/01/2022  | 3000         |
| Delta    | 05/01/2022   | 15/01/2022  | 5000         |
| Echo     | 12/01/2022   | 30/01/2022  | 1000         |

Para obtener todas las combinaciones el algoritmo realiza estos pasos:
- Ordenar los proyectos por fecha de inicio
- Obtener todas las combinaciones posibles:
  - Iterar todos los proyectos
  - El primer proyecto se añade al array de combinaciones válidas
  - Iteramos el siguiente elemento:
    - Si la fecha de inicio es igual o mayor a la fecha final del último proyecto lo añadimos en la lista de
    combinaciones válidas.
    - Si la fecha de inicio es anterior a la fecha final del primero, pero igual o mayor a la fecha del penúltimo, hacemos
    una copia de las combinaciones válidas, substituimos el último por el proyecto actual y reprocesamos esa nueva lista
    de proyectos creados
    - Si no se cumple ninguna de las anteriores y es la primera iteración de los proyectos, se vuelven a reprocesar los
    proyectos desde el proyecto actual.
- Buscar de entre todas las combinaciones obtenidas la que tiene mayor rentabilidad.

Con los datos anteriores estas son todas las posibles combinaciones:
- Alfa, Charlie, Echo (5000)
- Alfa, Delta (6000)
- Beta, Charlie, Echo (6000)
- Beta, Delta (7000)

Dando Beta y Delta como la mejor combinación con una rentabilidad de 7000.