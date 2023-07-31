# VIJVERWACHT CLIENT #
# This script detects movement from the camera and sends the recorded video to the server, once the movement stops, with a cap of 2 minutes.
# This script is being run on a Pi Zero with a NoIR camera module, OS is Raspbian light version (console only).

import time
import cv2
import datetime
import os
import requests


KEY = "admin"

def PublishRecording(FileName):
    URL = "http://vijverwacht.codecove.nl/web/api/api.php?endpoint=publish&key=" + KEY
    files = {'video': open(FileName, 'rb')}
    r = requests.post(URL, files=files)

    print(r.text)


# Record with opencv, because picamera is not supported by my OS.
# Test record a 5 sec video

# Record a 5 sec video with opencv
def RecordVideo():
    # Open the camera
    cap = cv2.VideoCapture(0)

    if not cap.isOpened():
        print("Error: Could not access the camera.")
        return

    # Get the frames per second (fps) of the camera
    fps = int(cap.get(cv2.CAP_PROP_FPS))

    # Set the video resolution to 640x480
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

    # Set the video filename and codec
    filename = "recorded_video.mp4"
    fourcc = cv2.VideoWriter_fourcc(*'mp4v')

    # Create the VideoWriter object
    out = cv2.VideoWriter(filename, fourcc, fps, (640, 480))

    print("Recording 5 seconds of video...")

    # Record the video for 5 seconds
    start_time = cv2.getTickCount()
    while (cv2.getTickCount() - start_time) / cv2.getTickFrequency() < 5:
        ret, frame = cap.read()
        if not ret:
            break

        # Write the frame to the video file
        out.write(frame)

        # Display the frame in a window (optional)
        cv2.imshow('Recording...', frame)
        if cv2.waitKey(1) & 0xFF == 27:  # Press 'Esc' to stop recording
            break

    print("Recording completed.")

    # Release the VideoCapture and VideoWriter objects
    cap.release()
    out.release()

    # Close all OpenCV windows
    cv2.destroyAllWindows()

    # Publish the video to the server
    PublishRecording(filename)

RecordVideo()