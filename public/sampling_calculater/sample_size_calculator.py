# Python program to find out the sample size
# With the population target
# Import the numpy module
from flask import Flask, jsonify, request, render_template, redirect
import numpy as np
import sys
app = Flask(__name__)
argv = (sys.argv)

def samplesizeN(N,ci1,d1):
    p= .5
    if ci1 == 0.70:
        numer = np.power(1.04,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(1.04,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1  == 0.75:
        numer = np.power(1.15,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(1.15,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.8:
        numer = np.power(1.28,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(1.28,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.85:
        numer = np.power(1.44,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(1.44,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.92:
        numer = np.power(1.75,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(1.75,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.95:
        numer = np.power(1.96,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(1.96,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.96:
        numer = np.power(2.05,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.05,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.98:
        numer = np.power(2.33,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.33,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    elif ci1 == 0.99:
        numer = np.power(2.58,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.58,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
        
    elif ci1 == 0.90:
        numer = np.power(1.645,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.58,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
            
    elif ci1 == 0.999:
        numer = np.power(3.29,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.58,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
        
    elif ci1 == 0.9999:
        numer = np.power(3.89,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.58,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
        
    elif ci1 == 0.99999:
        numer = np.power(4.42,2)*float(N)*p*(1-p)
        denoma, denomb = d1*d1*(float(N)-1), np.power(2.58,2)*p*(1-p)
        denom = denoma + denomb
        ssize = numer/denom
    return int(np.ceil(ssize))

@app.route('/value-put', methods=['GET', 'POST'])
def upload_file():
    if request.method == 'POST':
        N = request.values['sample']
        ci = request.values['ci']
        d = request.values['margin']
        global d1
        global ci1 
        d1 = float(d)
        ci1 = float(ci)
        print(request.values['ci'])


        resp = jsonify({'message' : 'File successfully uploaded'})
        finalResponse = samplesizeN(N,ci1,d1)
        resp.status_code = 201
        return jsonify(finalResponse)

if __name__ == "__main__":
    app.run(host='0.0.0.0', port="8000", debug = False)
#    Output: n: 379




