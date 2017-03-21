# LPP2014
Práctica Lenguajes de Programación y Procesadores 2014/15

## Repositorio de ejercicios de programación

### Enunciado

Se desea modelar mediante XML‐Schema un repositorio de ejercicios de programación. Típicamente un ejercicio de programación corresponde a un enunciado, contiene información que permite ubicarlo en un tipo de asignatura o de lenguaje de programación, puede contener una o varias soluciones, etc.

El repositorio podrá contener más de 1 ejercicio de programación y cada uno constará de la siguiente información:

 - Identificador (obligatorio)
 - Enunciado (obligatorio)
 - Nivel (1=principiante, 2=intermedio, 3=avanzado, 4=superior)
 - Orientado a un lenguaje de programación (booleano)
 - Solución (de 0 a n)
   - Lenguaje (contendrá el lenguaje de programación de la solución o pseudocódigo). El tipo de dato deberá ser un enumerado.
   - Nivel de dificultad de la solución (1=principiante, 2=intermedio, 3=avanzado, 4=superior)
   - Código (contendrá el código de la solución estructurado en líneas)
     - Línea (de 1 a n)
     
Se pide realizar un XML‐Schema que permita modelar documentos con XML con la estructura descrita. Para ello deberá utilizarse, en la medida de lo posible, declaraciones de tipo global, de manera que puedan ser reutilizadas dentro del mismo o de otros XML‐Schema. Se valorará la utilización de tipos de datos predefinidos adecuados al contenido de los elementos y atributos, así como la creación de nuevos tipos de datos cuando se estime oportuno.

Desarrolla un programa en PHP para transformar documentos XML válidos respecto al XML‐Schema diseñado en el apartado anterior a documentos HTML. Para acceder al contenido de los documentos XML habrá que utilizar una extensión con un enfoque dirigido por eventos, como XML‐Parser. Una referencia detallada de la extensión XML‐Parser de PHP se puede encontrar en http://www.php.net/manual/en/book.xml.php.
