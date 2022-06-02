# Gestion deverminage

Make with [![My Skills](https://skills.thijs.gg/icons?i=php,js,html,css,arduino)](https://skills.thijs.gg)

Here is an explanation showing the creation of a supervision system in burn-in test on LED technology
products with Arduino, but also the development of a website allowing the visualization of these
data with an administrator space. Several details are given on the algorithm of microcontrollers, and
the logic of design and realization of the system in industrial environment.

# Feature

If  you want, you could find the code documentation in doc2 folder (you must run it in your browser)

# Informations

More information ? You could find French rapport about this project here : [Rapport_de_stage_Adrien_NICOLAS_final.pdf](https://github.com/Adrien-Nicolas/Gestion_deverminage/files/8824539/Rapport_de_stage_Adrien_NICOLAS_final.pdf)

# Arduino Part

At first, we thought of the simplest way, i.e. to send data all the time
data every 200 ms, but this method has many flaws:

  1. If a blink happens between these 200 ms, it will not be detected and sent by the Arduino.
  by the Arduino.
  
  2. If the tubes are turned off, and thus with no risk of flashing, the Arduino would still send
  data at the same frequency as if they were on.
  To solve the first problem, we thought of recovering if there is a voltage in the relay (where the
  to supply the products) because if the products are not supplied then they cannot be switched on.
  then they can't be switched on.
  With this improvement, we went from 20 million data recorded to 200 times less data
  light intensity data on a basic 19:30 cycle, which is not negligible.


# Software Part

The visualization of the sensor data is done in two different
two different optimal ways, and must be as clear and
as clear and remarkable as possible:

See here : ![graph](https://user-images.githubusercontent.com/73825898/171646849-6ff1aaa2-c17a-4668-9990-12292029184d.png)


  - When we arrive on this page, we can directly observe the number of
  directly observe the number of products
  compliant or not. But when we look at the
  different sensors we observe on the right, in
  either compliant in green or non-compliant in red, it is therefore
  in red, it is therefore possible to see where the
  failing products.
  
  - Once we have chosen a sensor, we can view its data by clicking on the
  data by clicking on the sensor, a graph appears.
  sensor, a graph appears, during the loading, there is the
  loading, there is the logo of the company which turns
  to entertain the user. On the graph, we
  observe two curves and two constants:
  
    1. The first curve represents the light intensity which goes from 0 (no light intensity) to
    intensity) to 1023 (maximum light intensity). This interval represents the values
    values acquired by the microcontroller. The values obtained have no unit, but are used as a reference
    serve as a reference to obtain a reliable detection threshold. The curve is represented in
    blue
    
    2. The second curve is that of the temperature, the two curves have different axes to avoid that the
    to avoid that the curve of the temperature is crushed compared to the light intensity. This one
    is represented in orange
    
    3. The first constant is that of the low threshold, it is possible to modify its value when
    It is possible to modify its value by clicking on the configuration of the graph.
    
    4. The last constant is the one of the high threshold, it is also possible to modify the value in the same place as for the
    same place as for the low threshold. It is possible to hide one of the curves by clicking on
    its logo on the right side. These last two curves can allow the operator to
    visualize if the light intensity is correctly located in the interval defined on the time
    of the test.

