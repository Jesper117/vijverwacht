import cv2
import os
import requests
import platform
import time
import threading

KEY = "admin"

Sensitivity = .5

ActiveReports = 0
LatestReportUNIX = 0
ReportDebounce = True

RecordingCountdown = 0
Recording = False
video_writer = None

InitialRecordingIncrement = 20
RepetitiveMotionIncrement = 5
InactiveCap = 15
AbsoluteRecordingCap = 120

DisplayAttached = False
if platform.system() == "Windows":
    DisplayAttached = True

def PublishRecording(FileName):
    print("Publishing recording...")

    URL = "http://vijverwacht.codecove.nl/web/api/api.php?endpoint=publish&key=" + KEY
    files = {"video": open(FileName, "rb")}
    r = requests.post(URL, files=files)

    print("Publishing completed.")


def ReportCooldown():
    global ActiveReports

    time.sleep(10)

    if ActiveReports > 0:
        ActiveReports -= 1


def ReportMotion():
    global ActiveReports
    global ReportDebounce
    global RecordingCountdown
    global Recording
    global LatestReportUNIX
    global video_writer

    if not ReportDebounce:
        ReportDebounce = True

        ActiveReports += 1
        LatestReportUNIX = int(time.time())

        ReportCooldownThread = threading.Thread(target=ReportCooldown)
        ReportCooldownThread.start()

        time.sleep(2)
        ReportDebounce = False

        if ActiveReports >= 2:
            if Recording:
                RecordingCountdown += RepetitiveMotionIncrement
            else:
                RecordingCountdown = InitialRecordingIncrement
                Recording = True

                video_writer = cv2.VideoWriter("recording.mp4", cv2.VideoWriter_fourcc(*"mp4v"), 20, (640, 480))

                print("Recording started.")


def RecordingControlLoop():
    global RecordingCountdown
    global Recording
    global video_writer

    while True:
        CurrentUNIX = int(time.time())

        if Recording:
            if RecordingCountdown <= 0 or RecordingCountdown >= AbsoluteRecordingCap or CurrentUNIX - LatestReportUNIX >= InactiveCap:
                Recording = False
                RecordingCountdown = 0

                if video_writer is not None:
                    video_writer.release()
                    video_writer = None

                print("Recording stopped.")

                PublishRecording("recording.mp4")
            else:
                RecordingCountdown -= 1

        time.sleep(1)


def DetectMotion(frame, background_subtractor):
    fgmask = background_subtractor.apply(frame, learningRate=0.01)
    return cv2.countNonZero(fgmask) > 10000


def MotionDetectionMain():
    cap = cv2.VideoCapture(0)

    if not cap.isOpened():
        print("Error: Could not access the camera.")
        exit()

    fps = int(cap.get(cv2.CAP_PROP_FPS))

    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

    if DisplayAttached:
        cv2.namedWindow("Motion Detection", cv2.WINDOW_NORMAL)
        cv2.resizeWindow("Motion Detection", 640, 480)

    background_subtractor = cv2.createBackgroundSubtractorMOG2()

    print("Camera is ready.")
    while True:
        ret, frame = cap.read()
        if not ret:
            break

        if DetectMotion(frame, background_subtractor):
            MotionDetectionThread = threading.Thread(target=ReportMotion)
            MotionDetectionThread.start()

        if Recording:
            if video_writer is not None:
                video_writer.write(frame)

        if DisplayAttached:
            cv2.imshow("vijverwacht", frame)

        cv2.waitKey(1)

    cap.release()

    if video_writer is not None:
        video_writer.release()

    if DisplayAttached:
        cv2.destroyWindow("vijverwacht")


MotionDetectionMainThread = threading.Thread(target=MotionDetectionMain)
MotionDetectionMainThread.start()

time.sleep(5)

RecordingControlThread = threading.Thread(target=RecordingControlLoop)
RecordingControlThread.start()

ReportDebounce = False

print("Motion detection is active.")