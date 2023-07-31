# VIJVERWACHT CLIENT #
# This script detects movement from the camera and sends the recorded video to the server, once the movement stops, with a cap of 2 minutes.
# This script is being run on a Pi Zero with a NoIR camera module, OS is Raspbian light version (console only).

import time
import picamera2
import datetime
import os
import requests

camera = picamera.PiCamera()

KEY = "admin"

def PublishRecording(FileName):
    URL = "http://vijverwacht.codecove.nl/api/api.php?endpoint=publish&key=" + KEY
    files = {'video': open(FileName, 'rb')}
    r = requests.post(URL, files=files)


camera.start_preview()
time.sleep(2)
camera.stop_preview()

camera.start_recording('test.h264')
camera.wait_recording(5)
camera.stop_recording()

PublishRecording('test.h264')