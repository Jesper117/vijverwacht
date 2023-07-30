import requests
import time
import os

URL = "https://raw.githubusercontent.com/Jesper117/vijverwacht/main/vijverwacht.py"
LastHash = ""

# Every 10 seconds, clone the file, save it as vijverwacht_temp.py, and compare the hash to the previous hash, if a new hash was found, delete the old file, rename the temp and run the new one.
# Keep in mind that we are running Raspbian Lite.
while True:
    r = requests.get(URL)

    with open("vijverwacht_temp.py", "wb") as code:
        code.write(r.content)
    with open("vijverwacht_temp.py", "r") as code:
        NewHash = hash(code.read())

    print("Comparing new hash: " + str(NewHash) + " vs last hash: " + str(LastHash))

    if NewHash != LastHash:
        print("New version found, updating.")

        LastHash = NewHash
        os.remove("vijverwacht.py")
        os.rename("vijverwacht_temp.py", "vijverwacht.py")
        os.system("python vijverwacht.py")

    time.sleep(1)