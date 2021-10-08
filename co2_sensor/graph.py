import time
import MySQLdb
import datetime
import matplotlib.pyplot as plt
import numpy as np
#
# to make a histgram and time vs co2 concentration graph using matplotlib
#
connetor = None

def db_read(t_range):
    previous_t = datetime.datetime.now()
    v_list = []
    a_time = []
    index = 1
    try:
        connector = MySQLdb.connect(host="localhost", db="co2", user="root", passwd="root", charset="utf8")
        cursor = connector.cursor()
        # request records
        cursor.execute('select * from pythonco2 order by t desc')
        for (id, t, density) in cursor:
            if (previous_t - t) < datetime.timedelta(hours = t_range):
                v_list.append(density)
                a_time.append(index)
                index += 1
            else:
                break
        #    print(f"{id} {t} {density}")
        v_list.reverse()

    except Exception as e:
        print(f"Error Occurred: {e}")

    finally:
        cursor.close()
        connector.close()

        return v_list, a_time

def h_graph_make(np_data):
    fig = plt.figure()
    plt.hist(np_data, bins=100, ec='black')
    plt.title("co2 concentration(histgram)")
    plt.xlabel("co2[ppm]")
    plt.ylabel("freq.")
    plt.xlim(300, 2000)
    plt.grid()
    fig.savefig("freq.png")
    return

def t_graph_make(p2, p1):
    fig = plt.figure()
    plt.plot(p1, p2, marker="o", color = "red", linestyle = "--")
    plt.title("co2 concentration(recent 4hours)")
    plt.xlabel("time[min]")
    plt.ylabel("co2[ppm]")
    plt.grid()
    fig.savefig("time_axis.png")
    return

# t_range unit is 'hour'
t_range = 4
np_data = np.array([])
v_list = db_read(t_range)
# to get value by first tuple
c_value = v_list[0]
for value in c_value:
    np_data = np.append(np_data, value)

h_graph_make(np_data)
# to prepare x and y axis data
p1 = v_list[0]
p2 = v_list[1]

t_graph_make(p1, p2)
