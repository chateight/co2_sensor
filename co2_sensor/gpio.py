import RPi.GPIO as GPIO
import mh_z19
import time
import MySQLdb
import datetime
import subprocess
# BCM PIN ASSIGN
# ledは23:blue, 24:yellow, 25:red
BLUE = 23
YELLOW =24
RED = 25
# Shut down sw is assigned to 17
SHUTDOWN = 17

def init():
    # initialise Pins
    GPIO.setwarnings(False)
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(BLUE, GPIO.OUT)
    GPIO.setup(YELLOW, GPIO.OUT)
    GPIO.setup(RED, GPIO.OUT)
    GPIO.output(BLUE, True)
    GPIO.output(YELLOW, True)
    GPIO.output(RED, True)
# switchの割り当てとイベント待ち
    GPIO.setup(SHUTDOWN, GPIO.IN)
    GPIO.setup(SHUTDOWN, GPIO.IN, pull_up_down=GPIO.PUD_UP)
    return


def read_store():
    val = mh_z19.read()
    density = val['co2']
    # comment out when run as a background task
    #print(val['co2'])
    if density < 1000:
        GPIO.output(BLUE, False)
        GPIO.output(YELLOW, True)
        GPIO.output(RED, True)
    elif density < 2000:
        GPIO.output(BLUE, True)
        GPIO.output(YELLOW, False)
        GPIO.output(RED, True)
    else:
        GPIO.output(BLUE, True)
        GPIO.output(YELLOW, True)
        GPIO.output(RED, False)
    connector = MySQLdb.connect(host="localhost", db="co2", user="root", passwd="root", charset="utf8")
    cursor = connector.cursor()
    #sql="create table pythonco2(id int, t datetime, density int);"
    #cursor.execute(sql)
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    id = 1
    # insert a record
    cursor.execute('insert into pythonco2 (id, t, density) values (%s, %s, %s)', (id, now, density))
    cursor.close()
    # without commit(), sql excute do not affect table records.
    connector.commit()
    connector.close()
    return

def handle_sw_input():
    def switch_callback(gpio_pin):
        subprocess.call('sudo shutdown -h now', shell=True)
    #
    GPIO.add_event_detect(SHUTDOWN, GPIO.FALLING,bouncetime=250)
    # when the sw was pushed, call the 'call back routine' 
    GPIO.add_event_callback(SHUTDOWN, switch_callback) 
    return

init()
handle_sw_input()
# wait for mysql start up
time.sleep(5)
# repeat measure and store
while True:
    read_store()
    time.sleep(60)


