import os
import cv2
import numpy as np
from flask import Flask, request, jsonify
from flask_cors import CORS
import base64
import io
from PIL import Image

app = Flask(__name__)
CORS(app)

# Paths to models
MODEL_DIR = os.path.join(os.getcwd(), 'models')
YUNET_PATH = os.path.join(MODEL_DIR, 'face_detection_yunet.onnx')
SFACE_PATH = os.path.join(MODEL_DIR, 'face_recognition_sface.onnx')

# Path to directory containing member face images
FACES_DIR = os.path.join(os.getcwd(), 'storage', 'app', 'public', 'member_faces')

if not os.path.exists(FACES_DIR):
    os.makedirs(FACES_DIR)

# Initialize OpenCV Face Detector and Recognizer
detector = cv2.FaceDetectorYN.create(
    model=YUNET_PATH,
    config="",
    input_size=(320, 320),
    score_threshold=0.6, # Lowered from 0.9 for better detection in varied conditions
    nms_threshold=0.3,
    top_k=5000,
    backend_id=cv2.dnn.DNN_BACKEND_OPENCV,
    target_id=cv2.dnn.DNN_TARGET_CPU
)

recognizer = cv2.FaceRecognizerSF.create(
    model=SFACE_PATH,
    config="",
    backend_id=cv2.dnn.DNN_BACKEND_OPENCV,
    target_id=cv2.dnn.DNN_TARGET_CPU
)

known_face_features = []
known_face_ids = []

def enhance_image(img):
    """Apply CLAHE to improve contrast for better detection/recognition"""
    try:
        lab = cv2.cvtColor(img, cv2.COLOR_BGR2LAB)
        l, a, b = cv2.split(lab)
        clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8,8))
        cl = clahe.apply(l)
        limg = cv2.merge((cl, a, b))
        enhanced = cv2.cvtColor(limg, cv2.COLOR_LAB2BGR)
        return enhanced
    except:
        return img

def load_known_faces():
    global known_face_features, known_face_ids
    known_face_features = []
    known_face_ids = []
    
    print(f"Loading and analyzing known faces from {FACES_DIR}...")
    for filename in os.listdir(FACES_DIR):
        if filename.endswith(('.jpg', '.png', '.jpeg')):
            path = os.path.join(FACES_DIR, filename)
            try:
                img = cv2.imread(path)
                if img is None: continue
                
                # Enhance training image
                img = enhance_image(img)
                
                height, width, _ = img.shape
                detector.setInputSize((width, height))
                _, faces = detector.detect(img)
                
                if faces is not None:
                    aligned_face = recognizer.alignCrop(img, faces[0])
                    feature = recognizer.feature(aligned_face)
                    known_face_features.append(feature)
                    known_face_ids.append(os.path.splitext(filename)[0])
                    print(f"Validated & Cached: {os.path.splitext(filename)[0]}")
            except Exception as e:
                print(f"Error loading {filename}: {e}")
    print(f"Ready. {len(known_face_features)} members in memory.")

@app.route('/recognize', methods=['POST'])
def recognize():
    try:
        data = request.json
        if not data or 'image' not in data:
            return jsonify({'status': False, 'message': 'No image data provided'}), 400
            
        image_data = data['image'].split(',')[1] if ',' in data['image'] else data['image']
        image_bytes = base64.b64decode(image_data)
        image = Image.open(io.BytesIO(image_bytes)).convert('RGB')
        frame = np.array(image)
        frame = cv2.cvtColor(frame, cv2.COLOR_RGB2BGR)
        
        # PRE-PROCESSING ENHANCEMENT
        enhanced_frame = enhance_image(frame)
        
        height, width, _ = enhanced_frame.shape
        detector.setInputSize((width, height))
        _, faces = detector.detect(enhanced_frame)
        
        member_id = None
        max_score = -1.0
        
        if faces is not None:
            print(f"Detected {len(faces)} potential faces")
            for face in faces:
                aligned_face = recognizer.alignCrop(enhanced_frame, face)
                feature = recognizer.feature(aligned_face)
                
                if not known_face_features: break
                
                for idx, known_feature in enumerate(known_face_features):
                    score = recognizer.match(feature, known_feature, cv2.FaceRecognizerSF_FR_COSINE)
                    
                    # Strictly thresholding: SFace standard is 0.363
                    # We use 0.40 for a balance between speed and precision
                    if score > 0.40: 
                        if score > max_score:
                            max_score = score
                            member_id = known_face_ids[idx]
        
        if member_id:
            print(f"MATCH: {member_id} (Confidence: {round(max_score * 100, 2)}%)")
            return jsonify({
                'status': True,
                'member_id': member_id,
                'confidence': float(max_score),
                'message': 'Face recognized'
            })
        else:
            print("No matching face found above threshold.")
            return jsonify({'status': False, 'message': 'No matching face found'})
            
    except Exception as e:
        print(f"Critical error: {e}")
        return jsonify({'status': False, 'message': str(e)}), 500

@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status': True, 
        'message': 'AI Service is running',
        'members_loaded': len(known_face_features)
    })

@app.route('/reload', methods=['GET'])
def reload():
    load_known_faces()
    return jsonify({'status': True, 'message': f'Reloaded {len(known_face_features)} faces'})

if __name__ == '__main__':
    load_known_faces()
    # Run server on port 5000
    app.run(host='0.0.0.0', port=5000, debug=False)
