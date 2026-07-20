"""
Minimal HTTP wrapper around the `nuclei` CLI so your PHP dashboard
can trigger scans over HTTP instead of shelling into the container.

POST /scan   { "target": "https://client-site.com" }
  -> runs `nuclei -u <target> -jsonl`, returns parsed JSON findings

This is intentionally simple/synchronous. For long scans, call it
from a queued job on the PHP side (don't block a web request on it).
"""
import json
import subprocess
from flask import Flask, request, jsonify

app = Flask(__name__)

ALLOWED_FLAGS = ["-jsonl", "-silent", "-timeout", "10"]

@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"})

@app.route("/scan", methods=["POST"])
def scan():
    data = request.get_json(silent=True) or {}
    target = data.get("target")

    if not target or not target.startswith(("http://", "https://")):
        return jsonify({"error": "target must be a full http(s) URL"}), 400

    cmd = ["nuclei", "-u", target] + ALLOWED_FLAGS

    try:
        result = subprocess.run(
            cmd, capture_output=True, text=True, timeout=600
        )
    except subprocess.TimeoutExpired:
        return jsonify({"error": "scan timed out after 600s"}), 504

    findings = []
    for line in result.stdout.splitlines():
        line = line.strip()
        if not line:
            continue
        try:
            findings.append(json.loads(line))
        except json.JSONDecodeError:
            continue

    return jsonify({
        "target": target,
        "findings": findings,
        "count": len(findings),
        "stderr": result.stderr[-2000:] if result.stderr else None,
    })

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=9002)