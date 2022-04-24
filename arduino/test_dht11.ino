#include <dht11.h>
#define DHT11PIN 7 // broche DATA -> broche 4
dht11 DHT11;
void setup()
{
  Serial.begin(9600);
 
  while (!Serial) {
    // wait for serial port to connect. Needed for native USB (LEONARDO)
  }
}
 
void loop()
{
  DHT11.read(DHT11PIN);
float humidite = DHT11.humidity;
float temps = DHT11.temperature;


 /////////affichage/////////////////
  
  Serial.print("Humidité (%): ");
  Serial.print((float)DHT11.humidity, 2);
  Serial.print("\t");
  Serial.print("Température (°C): ");
  Serial.println(temps);
  delay(500);

}

  
