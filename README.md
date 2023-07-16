![Vijverwacht](https://i.imgur.com/3jeYOyw.png)

<h3>Beschrijving</h3>
Vijverwacht is een project waar opnames van je vijver of tuin automatisch worden geüpload vanaf een Raspberry Pi. <br>
De online versie is te zien op https://vijverwacht.codecove.nl.

<h3>Hardware</h3>
Om de opnames te maken wordt gebruik gemaakt van een Raspberry Pi Zero W met een Pi NoIR camera en PIR motion sensor. <br>
Deze hardware is in een 3D-geprinte behuizing geplaatst en wordt voorzien van stroom via een standaard Raspberry Pi stroomadapter. <br>


- [Raspberry Pi Zero W](https://www.raspberrypi.org/products/raspberry-pi-zero-w/)
- [Pi NoIR Camera V2](https://www.raspberrypi.org/products/pi-noir-camera-v2/)
- [Stroomadapter](https://www.raspberrypi.org/products/raspberry-pi-universal-power-supply/)
- [PIR Motion Sensor](https://www.adafruit.com/product/189)
- [Behuizing 3D model](https://google.com)

Bij elkaar kost dit ongeveer €60,-. <br>

<h3>Software</h3>
De Raspberry Pi draait op Raspbian Lite. Dit kan gedownload worden vanaf de Raspberry Pi website https://www.raspberrypi.org/downloads/raspbian . <br>
Het script is geschreven in Python 3.7. <br>

<h3>Installatie</h3>
De volgende pin layout wordt gebruikt voor de NoIR camera en PIR motion sensor: <br>
![PinLayout](https://i.imgur.com/iOh5V4p.png)


Om het script te installeren en te draaien moet de Raspberry Pi verbonden zijn met internet. <br>
De volgende commando's moeten worden uitgevoerd om het script te installeren:

```
sudo apt-get update
```
```
sudo apt-get install python3-pip
```
```
sudo pip3 install picamera
sudo pip3 install RPi.GPIO
sudo pip3 install requests
```

```
git clone https://github.com/Jesper117/vijverwacht/
cd vijverwacht
```

Pas vervolgens de *config.cfg* aan naar jouw host en token. <br> <br>
Afhankelijk van je config wordt het script automatisch gestart bij het opstarten van de Pi. <br> <br>
Wanneer je de software handmatig wilt starten kan dit met het volgende commando:

```
sudo python3 vijverwacht.py
```
Als alles goed is gegaan, print het script het volgende:

```
Vijverwacht is gestart.
```

<h3>Gebruik</h3>
Het script maakt bij detectie van beweging een video totdat er 10 seconden geen beweging meer is, met een maximum van 2 minuten. <br>
Nadat de video is gemaakt wordt deze geüpload naar de server. <br>
De video's worden opgeslagen in de map *videos* in de root van het project. <br>
