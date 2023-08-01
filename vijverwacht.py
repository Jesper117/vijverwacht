import cv2
import os
import requests
import platform
import time

KEY = "admin"
DisplayAttached = True

def PublishRecording(FileName):
    print("Publishing recording...")

    # URL = "http://vijverwacht.codecove.nl/web/api/api.php?endpoint=publish&key=" + KEY
    # files = {'video': open(FileName, 'rb')}
    # r = requests.post(URL, files=files)

    print("Publishing completed.")

def RecordVideo(Duration):
    cap = cv2.VideoCapture(0)

    if not cap.isOpened():
        print("Error: Could not access the camera.")
        return

    fps = int(cap.get(cv2.CAP_PROP_FPS))

    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

    filename = "recording.mp4"
    fourcc = cv2.VideoWriter_fourcc(*'mp4v')

    out = cv2.VideoWriter(filename, fourcc, fps, (640, 480))

    print("Recording " + str(Duration) + " seconds of video...")

    if DisplayAttached:
        cv2.namedWindow("Recording", cv2.WINDOW_NORMAL)
        cv2.resizeWindow("Recording", 640, 480)

    start_time = cv2.getTickCount()
    while (cv2.getTickCount() - start_time) / cv2.getTickFrequency() < Duration:
        ret, frame = cap.read()
        if not ret:
            break

        out.write(frame)

        if DisplayAttached:
            cv2.imshow("Recording", frame)
            cv2.waitKey(1)

    cap.release()
    out.release()

    if DisplayAttached:
        cv2.destroyWindow("Recording")

    print("Recording completed.")

    PublishRecording(filename)

RecordVideo(7)
