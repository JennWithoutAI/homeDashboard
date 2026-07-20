"""
Minimal HTTP wrapper around testssl.sh so your PHP dashboard can
trigger TLS/cert/cipher checks over HTTP.

POST /scan   { "target": "client-site.com" }   (host:port, no scheme)
  -> runs `testssl.sh --jsonfile-pretty - <target>`, returns parsed JSON
"""
import json
import subprocess
import tempfile
import os
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"})

@app.route("/scan", methods=["POST"])
def scan():
    data = request.get_json(silent=True) or {}
    target = data.get("target")

    if not target:
        return jsonify({"error": "target (host or host:port) required"}), 400

    with tempfile.NamedTemporaryFile(suffix=".json", delete=False) as tmp:
        out_path = tmp.name

    cmd = ["testssl.sh", "--quiet", "--jsonfile", out_path, target]

    try:
        subprocess.run(cmd, capture_output=True, text=True, timeout=600)
    except subprocess.TimeoutExpired:
        return jsonify({"error": "scan timed out after 600s"}), 504
    finally:
        pass

    try:
        with open(out_path) as f:
            results = json.load(f)
    except (FileNotFoundError, json.JSONDecodeError):
        results = []
    finally:
        if os.path.exists(out_path):
            os.remove(out_path)

    return jsonify({"target": target, "findings": results, "count": len(results)})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=9001)