<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ansteches Ticket Booking API</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            body {
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #333;
                overflow-x: hidden;
            }
            
            .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
            
            .header {
                text-align: center;
                margin-bottom: 40px;
                animation: fadeInDown 1s ease-out;
            }
            
            .logo {
                font-size: 3rem;
                font-weight: 600;
                color: white;
                margin-bottom: 10px;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            }
            
            .subtitle {
                font-size: 1.3rem;
                color: rgba(255,255,255,0.9);
                margin-bottom: 20px;
            }
            
            .status {
                display: inline-block;
                padding: 8px 20px;
                background: rgba(40, 167, 69, 0.9);
                color: white;
                border-radius: 25px;
                font-size: 0.9rem;
                animation: pulse 2s infinite;
            }
            
            .main-content {
                display: grid;
                grid-template-columns: 1fr 400px;
                gap: 30px;
                margin-bottom: 40px;
            }
            
            .features-section {
                background: rgba(255,255,255,0.95);
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                animation: fadeInLeft 1s ease-out;
            }
            
            .chatbot-section {
                background: rgba(255,255,255,0.95);
                border-radius: 15px;
                padding: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                animation: fadeInRight 1s ease-out;
                position: relative;
                overflow: hidden;
            }
            
            .chatbot-header {
                display: flex;
                align-items: center;
                padding: 15px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                border-radius: 10px;
                margin-bottom: 20px;
            }
            
            .bot-avatar {
                width: 40px;
                height: 40px;
                background: rgba(255,255,255,0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                animation: bounce 2s infinite;
            }
            
            .bot-info h3 { margin: 0; font-size: 1.1rem; }
            .bot-info p { margin: 0; font-size: 0.8rem; opacity: 0.8; }
            
            .chat-messages {
                height: 300px;
                overflow-y: auto;
                padding: 10px;
                border: 1px solid #eee;
                border-radius: 10px;
                margin-bottom: 15px;
            }
            
            .message {
                margin-bottom: 15px;
                animation: slideInUp 0.5s ease-out;
            }
            
            .message.bot { text-align: left; }
            .message.user { text-align: right; }
            
            .message-bubble {
                display: inline-block;
                padding: 10px 15px;
                border-radius: 20px;
                max-width: 80%;
                word-wrap: break-word;
            }
            
            .message.bot .message-bubble {
                background: #f1f3f4;
                color: #333;
            }
            
            .message.user .message-bubble {
                background: #667eea;
                color: white;
            }
            
            .quick-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-bottom: 15px;
            }
            
            .quick-btn {
                padding: 10px;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-size: 0.9rem;
                transition: all 0.3s;
            }
            
            .quick-btn:hover {
                background: #5a6fd8;
                transform: translateY(-2px);
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }
            
            .feature {
                padding: 20px;
                background: #f8f9fa;
                border-radius: 10px;
                border-left: 4px solid #667eea;
                transition: transform 0.3s;
            }
            
            .feature:hover { transform: translateY(-5px); }
            
            .feature h3 {
                margin: 0 0 10px 0;
                color: #667eea;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .feature p {
                margin: 0;
                color: #666;
                font-size: 0.9rem;
            }
            
            .api-tester {
                background: rgba(255,255,255,0.95);
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.1);
                animation: fadeInUp 1s ease-out;
                margin-top: 20px;
            }
            
            .auth-section {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 10px;
                margin-bottom: 30px;
            }
            
            .auth-form {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr auto;
                gap: 15px;
                align-items: end;
            }
            
            .form-group { display: flex; flex-direction: column; }
            
            .form-group label {
                margin-bottom: 5px;
                font-weight: 500;
                color: #333;
            }
            
            .form-group input, .form-group select {
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 0.9rem;
            }
            
            .btn {
                padding: 12px 25px;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 0.9rem;
                font-weight: 500;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }
            
            .btn:hover {
                background: #5a6fd8;
                transform: translateY(-2px);
            }
            
            .btn-secondary { background: #6c757d; }
            .btn-secondary:hover { background: #5a6268; }
            .btn-success { background: #28a745; }
            .btn-success:hover { background: #218838; }
            
            .token-display {
                background: #e9ecef;
                padding: 10px;
                border-radius: 5px;
                font-family: monospace;
                font-size: 0.8rem;
                margin-top: 10px;
                word-break: break-all;
            }
            
            .api-endpoints {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-top: 30px;
            }
            
            .endpoint-group {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                border: 1px solid #e9ecef;
            }
            
            .endpoint-group h3 {
                margin: 0 0 15px 0;
                color: #667eea;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .endpoint {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                margin-bottom: 10px;
                overflow: hidden;
            }
            
            .endpoint-header {
                padding: 12px 15px;
                background: #f8f9fa;
                border-bottom: 1px solid #ddd;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: background 0.3s;
            }
            
            .endpoint-header:hover { background: #e9ecef; }
            
            .endpoint-method {
                font-weight: bold;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 0.8rem;
            }
            
            .method-get { background: #d4edda; color: #155724; }
            .method-post { background: #d1ecf1; color: #0c5460; }
            .method-put { background: #fff3cd; color: #856404; }
            .method-delete { background: #f8d7da; color: #721c24; }
            
            .endpoint-content {
                padding: 15px;
                display: none;
            }
            
            .endpoint-content.show {
                display: block;
                animation: slideDown 0.3s ease-out;
            }
            
            .response-console {
                background: #1e1e1e;
                color: #00ff00;
                padding: 20px;
                border-radius: 8px;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                min-height: 250px;
                max-height: 500px;
                overflow-y: auto;
                margin-top: 15px;
                white-space: pre-wrap;
                border: 2px solid #333;
                position: relative;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            }
            
            .response-console:hover {
                border-color: #667eea;
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
                transform: translateY(-2px);
            }
            
            .response-console::before {
                content: '$ Terminal Output';
                position: absolute;
                top: -2px;
                left: 15px;
                background: #1e1e1e;
                color: #00ff00;
                padding: 2px 10px;
                font-size: 0.7rem;
                border-radius: 4px;
                font-weight: bold;
            }
            
            .console-header {
                background: #2d2d2d;
                color: #d4d4d4;
                padding: 12px 15px;
                border-radius: 8px 8px 0 0;
                font-weight: bold;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #444;
                font-family: 'Courier New', monospace;
            }
            
            .console-controls {
                display: flex;
                gap: 8px;
                align-items: center;
            }
            
            .console-btn {
                padding: 4px 10px;
                background: #444;
                color: #d4d4d4;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.7rem;
                transition: all 0.3s;
                font-family: 'Courier New', monospace;
            }
            
            .console-btn:hover {
                background: #667eea;
                color: white;
            }
            
            .terminal-dots {
                display: flex;
                gap: 6px;
            }
            
            .terminal-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
            }
            
            .dot-red { background: #ff5f56; }
            .dot-yellow { background: #ffbd2e; }
            .dot-green { background: #27ca3f; }
            
            .api-tester-interface {
                background: #fff;
                border-radius: 12px;
                padding: 0;
                margin-bottom: 20px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                border: 1px solid #e0e0e0;
            }
            
            .request-builder {
                background: linear-gradient(135deg, #667eea, #764ba2);
                padding: 20px;
                border-radius: 12px 12px 0 0;
            }
            
            .method-url-section {
                display: flex;
                gap: 10px;
                align-items: center;
            }
            
            .method-selector {
                min-width: 100px;
            }
            
            .method-dropdown {
                padding: 12px 15px;
                border: none;
                border-radius: 8px;
                background: #fff;
                font-weight: bold;
                color: #28a745;
                cursor: pointer;
                font-size: 0.9rem;
            }
            
            .url-input {
                flex: 1;
            }
            
            .url-field {
                width: 100%;
                padding: 12px 15px;
                border: none;
                border-radius: 8px;
                background: #fff;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                color: #333;
            }
            
            .send-btn {
                padding: 12px 25px;
                background: #28a745;
                color: white;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-weight: bold;
                font-size: 0.9rem;
                transition: all 0.3s;
                white-space: nowrap;
            }
            
            .send-btn:hover {
                background: #218838;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            }
            
            .request-tabs {
                background: #fff;
            }
            
            .tab-headers {
                display: flex;
                border-bottom: 1px solid #e0e0e0;
                background: #f8f9fa;
            }
            
            .tab-header {
                padding: 15px 20px;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                transition: all 0.3s;
                font-weight: 500;
                color: #666;
                font-size: 0.9rem;
            }
            
            .tab-header:hover {
                background: #e9ecef;
                color: #333;
            }
            
            .tab-header.active {
                color: #667eea;
                border-bottom-color: #667eea;
                background: #fff;
            }
            
            .tab-content {
                padding: 20px;
                display: none;
            }
            
            .tab-content.active {
                display: block;
                animation: fadeIn 0.3s ease;
            }
            
            .param-section, .headers-section, .body-section, .auth-section-details {
                min-height: 100px;
            }
            
            .tab-description {
                background: #e3f2fd;
                padding: 12px 15px;
                border-radius: 8px;
                border-left: 4px solid #2196f3;
                margin-bottom: 15px;
                font-size: 0.9rem;
            }
            
            .no-params, .no-body {
                text-align: center;
                color: #666;
                font-style: italic;
                padding: 30px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 2px dashed #ddd;
            }
            
            .header-item {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-bottom: 10px;
                padding: 10px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #e0e0e0;
            }
            
            .header-key, .header-value {
                flex: 1;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-family: 'Courier New', monospace;
                font-size: 0.8rem;
                background: #fff;
            }
            
            .auto-badge {
                padding: 4px 8px;
                background: #28a745;
                color: white;
                border-radius: 12px;
                font-size: 0.7rem;
                font-weight: bold;
                white-space: nowrap;
            }
            
            .auth-type {
                margin-bottom: 15px;
            }
            
            .auth-dropdown {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
                margin-top: 5px;
                background: #fff;
            }
            
            .token-info {
                margin-top: 15px;
            }
            
            .token-display-auth {
                padding: 12px 15px;
                background: #1e1e1e;
                color: #00ff00;
                border-radius: 6px;
                font-family: 'Courier New', monospace;
                font-size: 0.8rem;
                margin-top: 5px;
                border: 1px solid #333;
                word-break: break-all;
            }
            
            .response-section {
                margin-top: 20px;
            }
            
            .response-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding: 0 10px;
            }
            
            .response-header h3 {
                margin: 0;
                color: #333;
                font-size: 1.2rem;
            }
            
            .response-controls {
                display: flex;
                gap: 10px;
            }
            
            .control-btn {
                padding: 6px 12px;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.8rem;
                transition: all 0.3s;
            }
            
            .control-btn:hover {
                background: #5a6fd8;
                transform: translateY(-1px);
            }
            
            .response-status {
                padding: 4px 12px;
                border-radius: 12px;
                font-size: 0.8rem;
                font-weight: bold;
                background: #ffc107;
                color: #856404;
            }
            
            .response-status.success {
                background: #28a745;
                color: white;
            }
            
            .response-status.error {
                background: #dc3545;
                color: white;
            }
            
            .param-row {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-bottom: 10px;
                padding: 8px;
                background: #f8f9fa;
                border-radius: 6px;
                border: 1px solid #e0e0e0;
            }
            
            .param-checkbox {
                margin-right: 8px;
            }
            
            .param-key, .param-value {
                flex: 1;
                padding: 6px 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 0.8rem;
                font-family: 'Courier New', monospace;
            }
            
            .param-description {
                flex: 2;
                font-size: 0.8rem;
                color: #666;
                font-style: italic;
            }
            
            .body-type-selector {
                margin-bottom: 15px;
            }
            
            .body-type-tabs {
                display: flex;
                border: 1px solid #ddd;
                border-radius: 6px;
                overflow: hidden;
                background: #fff;
            }
            
            .body-type-tab {
                padding: 8px 16px;
                cursor: pointer;
                border-right: 1px solid #ddd;
                background: #f8f9fa;
                transition: all 0.3s;
                font-size: 0.8rem;
            }
            
            .body-type-tab:last-child {
                border-right: none;
            }
            
            .body-type-tab.active {
                background: #667eea;
                color: white;
            }
            
            .body-type-tab:hover:not(.active) {
                background: #e9ecef;
            }
            
            .json-editor-enhanced {
                width: 100%;
                min-height: 200px;
                padding: 15px;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                background: #1e1e1e;
                color: #00ff00;
                resize: vertical;
                transition: border-color 0.3s;
            }
            
            .json-editor-enhanced:focus {
                border-color: #667eea;
                outline: none;
                box-shadow: 0 0 15px rgba(102, 126, 234, 0.3);
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .form-data-section {
                border: 2px solid #e0e0e0;
                border-radius: 8px;
                padding: 15px;
                background: #fafafa;
                margin-top: 10px;
            }
            
            .file-upload-section {
                margin-top: 15px;
            }
            
            .file-upload-area {
                border: 2px dashed #667eea;
                border-radius: 12px;
                padding: 40px 20px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s;
                background: #f8f9ff;
            }
            
            .file-upload-area:hover {
                border-color: #5a6fd8;
                background: #f0f2ff;
                transform: translateY(-2px);
            }
            
            .upload-placeholder p {
                margin: 5px 0;
                color: #333;
            }
            
            .selected-file {
                margin-top: 15px;
                padding: 15px;
                background: #e8f5e8;
                border: 1px solid #28a745;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .file-info {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #155724;
            }
            
            .file-info i {
                color: #28a745;
                font-size: 1.2rem;
            }
            
            .file-info button {
                background: #dc3545;
                color: white;
                border: none;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                cursor: pointer;
                font-size: 0.8rem;
            }
            
            .body-content {
                margin-top: 15px;
            }
            
            .method-dropdown[disabled] {
                opacity: 0.8;
                cursor: not-allowed;
            }
            
            .url-field[readonly] {
                background: #f8f9fa;
                color: #495057;
            }
            
            .testing-indicator {
                display: inline-block;
                width: 16px;
                height: 16px;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #667eea;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin-left: 10px;
            }
            
            .send-btn.testing {
                background: #6c757d;
                cursor: not-allowed;
            }
            
            .send-btn.testing:hover {
                transform: none;
                box-shadow: none;
            }
            
            .terminal-container.fullscreen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                z-index: 9999;
                background: #1e1e1e;
                border-radius: 0;
            }
            
            .terminal-container.fullscreen .response-console {
                height: calc(100vh - 60px);
                max-height: none;
                border-radius: 0;
            }
            
            .test-section {
                background: white;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                border: 1px solid #e0e0e0;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .json-editor {
                width: 100%;
                min-height: 120px;
                margin: 15px 0;
                padding: 15px;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                background: #f8f9fa;
                transition: all 0.3s;
                resize: vertical;
                line-height: 1.5;
            }
            
            .json-editor:focus {
                border-color: #667eea;
                outline: none;
                box-shadow: 0 0 15px rgba(102, 126, 234, 0.3);
                background: #fff;
            }
            
            .endpoint-content {
                padding: 0;
                display: none;
                background: #fafafa;
                border-radius: 0 0 8px 8px;
            }
            
            .endpoint-content.show {
                display: block;
                animation: slideDown 0.3s ease-out;
            }
            
            .endpoint {
                background: white;
                border: 2px solid #ddd;
                border-radius: 8px;
                margin-bottom: 15px;
                overflow: hidden;
                transition: all 0.3s ease;
            }
            
            .endpoint:hover {
                border-color: #667eea;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
                transform: translateY(-2px);
            }
            
            .endpoint-header {
                padding: 15px 20px;
                background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                border-bottom: 1px solid #ddd;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s;
                border-radius: 8px 8px 0 0;
            }
            
            .endpoint-header:hover {
                background: linear-gradient(135deg, #e9ecef, #dee2e6);
                transform: translateY(-1px);
            }
            
            .loading {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid #667eea;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            .hidden { display: none; }
            
            .user-info {
                background: #d4edda;
                color: #155724;
                padding: 10px 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                display: none;
            }
            
            .user-info.show {
                display: block;
                animation: slideInUp 0.5s ease-out;
            }
            
            @keyframes fadeInDown {
                from { opacity: 0; transform: translateY(-30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes fadeInLeft {
                from { opacity: 0; transform: translateX(-30px); }
                to { opacity: 1; transform: translateX(0); }
            }
            
            @keyframes fadeInRight {
                from { opacity: 0; transform: translateX(30px); }
                to { opacity: 1; transform: translateX(0); }
            }
            
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes slideInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes slideDown {
                from { opacity: 0; max-height: 0; }
                to { opacity: 1; max-height: 500px; }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-10px); }
                60% { transform: translateY(-5px); }
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            @media (max-width: 768px) {
                .main-content { grid-template-columns: 1fr; }
                .auth-form { grid-template-columns: 1fr; }
                .api-endpoints { grid-template-columns: 1fr; }
            }
        </style>
    </head>
    <body>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1 class="logo">🎫 Ansteches Ticket Booking API</h1>
                <p class="subtitle">Interactive API Testing Platform</p>
                <div class="status">✅ Server Running</div>
            </div>

            <!-- Main Content Grid -->
            <div class="main-content">
                <!-- Features Section -->
                <div class="features-section">
                    <h2 style="margin-bottom: 20px; color: #667eea;">🚀 Platform Features</h2>
                    <div class="features-grid">
                        <div class="feature">
                            <h3><i class="fas fa-lock"></i> Authentication</h3>
                            <p>Secure user authentication with Laravel Sanctum, supporting multiple user roles</p>
                        </div>
                        <div class="feature">
                            <h3><i class="fas fa-ticket-alt"></i> Booking System</h3>
                            <p>Complete booking management with seat selection and ticket validation</p>
                        </div>
                        <div class="feature">
                            <h3><i class="fas fa-credit-card"></i> Payment Gateway</h3>
                            <p>Integrated payment processing with SSLCommerz for secure transactions</p>
                        </div>
                        <div class="feature">
                            <h3><i class="fas fa-bus"></i> Fleet Management</h3>
                            <p>Vehicle and route management with real-time scheduling</p>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="/api/documentation" class="btn" target="_blank">
                            <i class="fas fa-book"></i> View Full Documentation
                        </a>
                    </div>
                </div>

                <!-- Chatbot Section -->
                <div class="chatbot-section">
                    <div class="chatbot-header">
                        <div class="bot-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="bot-info">
                            <h3>API Assistant</h3>
                            <p>Ready to help you test APIs</p>
                        </div>
                    </div>
                    
                    <div class="chat-messages" id="chatMessages">
                        <div class="message bot">
                            <div class="message-bubble">
                                👋 Welcome! I'm your API testing assistant. Click "Test API Access" below to get started with authentication and explore all endpoints!
                            </div>
                        </div>
                    </div>
                    
                    <div class="quick-actions">
                        <button class="quick-btn" onclick="scrollToTester()">🔧 Test API Access</button>
                        <button class="quick-btn" onclick="showDocumentation()">📚 Documentation</button>
                        <button class="quick-btn" onclick="showSystemInfo()">ℹ️ System Info</button>
                        <button class="quick-btn" onclick="clearChat()">🗑️ Clear Chat</button>
                    </div>
                </div>
            </div>

            <!-- API Tester Section -->
            <div class="api-tester">
                <h2 style="margin-bottom: 20px; color: #667eea;">🧪 Interactive API Tester</h2>
                
                <!-- User Info Display -->
                <div class="user-info" id="userInfo">
                    <strong>Logged in as:</strong> <span id="userName"></span> (<span id="userRole"></span>)
                    <button class="btn btn-secondary" style="float: right; padding: 5px 15px; font-size: 0.8rem;" onclick="logout()">Logout</button>
                </div>
                
                <!-- Authentication Section -->
                <div class="auth-section" id="authSection">
                    <h3 style="margin-bottom: 15px;">🔐 Authentication Required</h3>
                    <p style="margin-bottom: 15px;">Please login to test API endpoints:</p>
                    
                    <div class="auth-form">
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" id="email" placeholder="Enter your email" value="admin@example.com">
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" id="password" placeholder="Enter your password" value="password">
                        </div>
                        <div class="form-group">
                            <label>Role:</label>
                            <select id="userRoleSelect">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                                <option value="operator">Operator</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" onclick="loginUser()">Login & Test APIs</button>
                        </div>
                    </div>
                    
                    <div id="tokenDisplay" class="token-display hidden"></div>
                </div>

                <!-- API Endpoints Section -->
                <div class="api-endpoints hidden" id="apiEndpoints">
                    <!-- Authentication Endpoints -->
                    <div class="endpoint-group">
                        <h3><i class="fas fa-key"></i> Authentication</h3>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('auth-user')">
                                <div>
                                    <span class="endpoint-method method-get">GET</span>
                                    <span>/api/user</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="auth-user">
                                <div class="api-tester-interface">
                                    <!-- Method and URL Section -->
                                    <div class="request-builder">
                                        <div class="method-url-section">
                                            <div class="method-selector">
                                                <select class="method-dropdown" disabled>
                                                    <option value="GET" selected>GET</option>
                                                </select>
                                            </div>
                                            <div class="url-input">
                                                <input type="text" value="{{url('/api')}}/user" readonly class="url-field">
                                            </div>
                                            <button class="send-btn" onclick="testEndpoint('GET', '/api/user')">📡 Send Request</button>
                                        </div>
                                    </div>

                                    <!-- Request Configuration Tabs -->
                                    <div class="request-tabs">
                                        <div class="tab-headers">
                                            <div class="tab-header active" onclick="switchTab('auth-user', 'params')">📋 Params</div>
                                            <div class="tab-header" onclick="switchTab('auth-user', 'headers')">📄 Headers</div>
                                            <div class="tab-header" onclick="switchTab('auth-user', 'body')">📦 Body</div>
                                            <div class="tab-header" onclick="switchTab('auth-user', 'auth')">🔐 Auth</div>
                                        </div>

                                        <!-- Params Tab -->
                                        <div class="tab-content active" id="auth-user-params">
                                            <div class="param-section">
                                                <p class="tab-description">📝 <strong>Description:</strong> Get current authenticated user information</p>
                                                <p class="no-params">ℹ️ No parameters required for this endpoint</p>
                                            </div>
                                        </div>

                                        <!-- Headers Tab -->
                                        <div class="tab-content" id="auth-user-headers">
                                            <div class="headers-section">
                                                <div class="header-item">
                                                    <input type="text" value="Authorization" readonly class="header-key">
                                                    <input type="text" value="Bearer [TOKEN]" readonly class="header-value">
                                                    <span class="auto-badge">🔄 Auto</span>
                                                </div>
                                                <div class="header-item">
                                                    <input type="text" value="Accept" readonly class="header-key">
                                                    <input type="text" value="application/json" readonly class="header-value">
                                                    <span class="auto-badge">🔄 Auto</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Body Tab -->
                                        <div class="tab-content" id="auth-user-body">
                                            <div class="body-section">
                                                <p class="no-body">ℹ️ No request body required for GET requests</p>
                                            </div>
                                        </div>

                                        <!-- Auth Tab -->
                                        <div class="tab-content" id="auth-user-auth">
                                            <div class="auth-section-details">
                                                <div class="auth-type">
                                                    <label>🔐 <strong>Authentication Type:</strong></label>
                                                    <select class="auth-dropdown" disabled>
                                                        <option value="bearer" selected>Bearer Token</option>
                                                    </select>
                                                </div>
                                                <div class="token-info">
                                                    <label>🎫 <strong>Token:</strong></label>
                                                    <div class="token-display-auth" id="auth-user-token">Login required to see token</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Response Section -->
                                <div class="response-section">
                                    <div class="response-header">
                                        <h3>📡 Response</h3>
                                        <div class="response-controls">
                                            <button class="control-btn" onclick="clearTerminal('auth-user-response')">🗑️ Clear</button>
                                            <button class="control-btn" onclick="copyTerminal('auth-user-response')">📋 Copy</button>
                                            <button class="control-btn" onclick="toggleFullscreen('auth-user-response')">⛶ Fullscreen</button>
                                        </div>
                                    </div>
                                    <div class="terminal-container">
                                        <div class="console-header">
                                            <div class="terminal-dots">
                                                <div class="terminal-dot dot-red"></div>
                                                <div class="terminal-dot dot-yellow"></div>
                                                <div class="terminal-dot dot-green"></div>
                                            </div>
                                            <span>🖥️ Response Terminal</span>
                                            <div class="response-status" id="auth-user-status">⏳ Ready</div>
                                        </div>
                                        <div class="response-console" id="auth-user-response">🚀 Click "Send Request" to test this endpoint...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Endpoints -->
                    <div class="endpoint-group">
                        <h3><i class="fas fa-user"></i> User Profile</h3>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('profile-update')">
                                <div>
                                    <span class="endpoint-method method-post">POST</span>
                                    <span>/api/profile</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="profile-update">
                                <div class="api-tester-interface">
                                    <!-- Method and URL Section -->
                                    <div class="request-builder">
                                        <div class="method-url-section">
                                            <div class="method-selector">
                                                <select class="method-dropdown" disabled>
                                                    <option value="POST" selected style="color: #007bff;">POST</option>
                                                </select>
                                            </div>
                                            <div class="url-input">
                                                <input type="text" value="{{url('/api')}}/profile" readonly class="url-field">
                                            </div>
                                            <button class="send-btn" onclick="testProfileUpdate()">📡 Send Request</button>
                                        </div>
                                    </div>

                                    <!-- Request Configuration Tabs -->
                                    <div class="request-tabs">
                                        <div class="tab-headers">
                                            <div class="tab-header" onclick="switchTab('profile-update', 'params')">📋 Params</div>
                                            <div class="tab-header" onclick="switchTab('profile-update', 'headers')">📄 Headers</div>
                                            <div class="tab-header active" onclick="switchTab('profile-update', 'body')">📦 Body</div>
                                            <div class="tab-header" onclick="switchTab('profile-update', 'auth')">🔐 Auth</div>
                                        </div>

                                        <!-- Params Tab -->
                                        <div class="tab-content" id="profile-update-params">
                                            <div class="param-section">
                                                <p class="tab-description">📝 <strong>Description:</strong> Update user profile information including image upload</p>
                                                <p class="no-params">ℹ️ No URL parameters required for this endpoint</p>
                                            </div>
                                        </div>

                                        <!-- Headers Tab -->
                                        <div class="tab-content" id="profile-update-headers">
                                            <div class="headers-section">
                                                <div class="header-item">
                                                    <input type="text" value="Authorization" readonly class="header-key">
                                                    <input type="text" value="Bearer [TOKEN]" readonly class="header-value">
                                                    <span class="auto-badge">🔄 Auto</span>
                                                </div>
                                                <div class="header-item">
                                                    <input type="text" value="Accept" readonly class="header-key">
                                                    <input type="text" value="application/json" readonly class="header-value">
                                                    <span class="auto-badge">🔄 Auto</span>
                                                </div>
                                                <div class="header-item">
                                                    <input type="text" value="Content-Type" readonly class="header-key">
                                                    <input type="text" value="multipart/form-data" readonly class="header-value">
                                                    <span class="auto-badge">🔄 Auto</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Body Tab -->
                                        <div class="tab-content active" id="profile-update-body">
                                            <div class="body-section">
                                                <div class="body-type-selector">
                                                    <label><strong>📦 Request Body Type:</strong></label>
                                                    <div class="body-type-tabs">
                                                        <div class="body-type-tab" onclick="switchBodyType('profile-update', 'json')">JSON</div>
                                                        <div class="body-type-tab active" onclick="switchBodyType('profile-update', 'form')">Form Data</div>
                                                        <div class="body-type-tab" onclick="switchBodyType('profile-update', 'file')">File Upload</div>
                                                    </div>
                                                </div>

                                                <!-- JSON Body -->
                                                <div class="body-content" id="profile-update-json" style="display: none;">
                                                    <label><strong>JSON Data:</strong></label>
                                                    <textarea class="json-editor-enhanced" id="profile-json-data" placeholder="Enter JSON data here...">{\n  "name": "Updated Name",\n  "phone_number": "+8801700000000",\n  "email": "updated@example.com"\n}</textarea>
                                                </div>

                                                <!-- Form Data Body -->
                                                <div class="body-content" id="profile-update-form">
                                                    <label><strong>Form Data Fields:</strong></label>
                                                    <div class="form-data-section">
                                                        <div class="param-row">
                                                            <input type="checkbox" checked class="param-checkbox">
                                                            <input type="text" value="name" class="param-key" readonly>
                                                            <input type="text" value="Updated Name" class="param-value" id="form-name">
                                                            <span class="param-description">User's full name</span>
                                                        </div>
                                                        <div class="param-row">
                                                            <input type="checkbox" checked class="param-checkbox">
                                                            <input type="text" value="phone_number" class="param-key" readonly>
                                                            <input type="text" value="+8801700000000" class="param-value" id="form-phone">
                                                            <span class="param-description">User's phone number</span>
                                                        </div>
                                                        <div class="param-row">
                                                            <input type="checkbox" checked class="param-checkbox">
                                                            <input type="text" value="email" class="param-key" readonly>
                                                            <input type="text" value="updated@example.com" class="param-value" id="form-email">
                                                            <span class="param-description">User's email address</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- File Upload Body -->
                                                <div class="body-content" id="profile-update-file" style="display: none;">
                                                    <label><strong>🖼️ Profile Image Upload:</strong></label>
                                                    <div class="file-upload-section">
                                                        <div class="file-upload-area" onclick="document.getElementById('profile-image-input').click()">
                                                            <div class="upload-placeholder">
                                                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #667eea; margin-bottom: 10px;"></i>
                                                                <p><strong>Click to upload profile image</strong></p>
                                                                <p style="font-size: 0.8rem; color: #666;">Supported: JPG, PNG, GIF, SVG (Max: 2MB)</p>
                                                            </div>
                                                            <input type="file" id="profile-image-input" accept="image/*" style="display: none;" onchange="handleImageUpload(this)">
                                                        </div>
                                                        <div class="selected-file" id="selected-image" style="display: none;">
                                                            <div class="file-info">
                                                                <i class="fas fa-image"></i>
                                                                <span id="file-name"></span>
                                                                <span id="file-size"></span>
                                                                <button onclick="removeSelectedImage()">✕</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Auth Tab -->
                                        <div class="tab-content" id="profile-update-auth">
                                            <div class="auth-section-details">
                                                <div class="auth-type">
                                                    <label>🔐 <strong>Authentication Type:</strong></label>
                                                    <select class="auth-dropdown" disabled>
                                                        <option value="bearer" selected>Bearer Token</option>
                                                    </select>
                                                </div>
                                                <div class="token-info">
                                                    <label>🎫 <strong>Token:</strong></label>
                                                    <div class="token-display-auth" id="profile-update-token">Login required to see token</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Response Section -->
                                <div class="response-section">
                                    <div class="response-header">
                                        <h3>📡 Response</h3>
                                        <div class="response-controls">
                                            <button class="control-btn" onclick="clearTerminal('profile-update-response')">🗑️ Clear</button>
                                            <button class="control-btn" onclick="copyTerminal('profile-update-response')">📋 Copy</button>
                                            <button class="control-btn" onclick="toggleFullscreen('profile-update-response')">⛶ Fullscreen</button>
                                        </div>
                                    </div>
                                    <div class="terminal-container">
                                        <div class="console-header">
                                            <div class="terminal-dots">
                                                <div class="terminal-dot dot-red"></div>
                                                <div class="terminal-dot dot-yellow"></div>
                                                <div class="terminal-dot dot-green"></div>
                                            </div>
                                            <span>🖥️ Response Terminal</span>
                                            <div class="response-status" id="profile-update-status">⏳ Ready</div>
                                        </div>
                                        <div class="response-console" id="profile-update-response">🚀 Click "Send Request" to test profile update...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Endpoints -->
                    <div class="endpoint-group">
                        <h3><i class="fas fa-ticket-alt"></i> Bookings</h3>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('bookings-list')">
                                <div>
                                    <span class="endpoint-method method-get">GET</span>
                                    <span>/api/bookings</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="bookings-list">
                                <div class="test-section">
                                    <p><strong>Description:</strong> Get user's bookings</p>
                                    <button class="btn" onclick="testEndpoint('GET', '/api/bookings')"> 🚀 Test Endpoint</button>
                                </div>
                                
                                <div class="terminal-container">
                                    <div class="console-header">
                                        <div class="terminal-dots">
                                            <div class="terminal-dot dot-red"></div>
                                            <div class="terminal-dot dot-yellow"></div>
                                            <div class="terminal-dot dot-green"></div>
                                        </div>
                                        <span>API Response Terminal</span>
                                        <div class="console-controls">
                                            <button class="console-btn" onclick="clearTerminal('bookings-list-response')">Clear</button>
                                            <button class="console-btn" onclick="copyTerminal('bookings-list-response')">Copy</button>
                                            <button class="console-btn" onclick="toggleFullscreen('bookings-list-response')">⛶</button>
                                        </div>
                                    </div>
                                    <div class="response-console" id="bookings-list-response">Click "Test Endpoint" to see the API response here...</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('bookings-create')">
                                <div>
                                    <span class="endpoint-method method-post">POST</span>
                                    <span>/api/bookings</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="bookings-create">
                                <p>Create a new booking</p>
                                <textarea id="booking-data" style="width: 100%; height: 120px; margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder='Booking data'>{\n  "from_station": "Dhaka",\n  "to_station": "Chittagong",\n  "journey_date": "2025-08-28",\n  "seat_type": "AC",\n  "number_of_seats": 2,\n  "total_fare": 60,\n  "route_id": 1,\n  "seat_number": ["A1", "A2"],\n  "payment_method": "cash"\n}</textarea>
                                <button class="btn" onclick="testEndpointWithData('POST', '/api/bookings', 'booking-data')">Test Endpoint</button>
                                <div class="response-console" id="bookings-create-response"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Routes Endpoints -->
                    <div class="endpoint-group">
                        <h3><i class="fas fa-route"></i> Routes</h3>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('routes-search')">
                                <div>
                                    <span class="endpoint-method method-get">GET</span>
                                    <span>/api/routes/search</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="routes-search">
                                <p>Search available routes</p>
                                <div style="margin: 10px 0;">
                                    <input type="text" id="origin" placeholder="Origin" value="Dhaka" style="margin: 5px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    <input type="text" id="destination" placeholder="Destination" value="Chittagong" style="margin: 5px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    <input type="date" id="journey_date" value="2025-08-28" style="margin: 5px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                </div>
                                <button class="btn" onclick="testRouteSearch()">Test Endpoint</button>
                                <div class="response-console" id="routes-search-response"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicles Endpoints -->
                    <div class="endpoint-group">
                        <h3><i class="fas fa-bus"></i> Vehicles</h3>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('vehicles-list')">
                                <div>
                                    <span class="endpoint-method method-get">GET</span>
                                    <span>/api/vehicles</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="vehicles-list">
                                <p>Get all vehicles</p>
                                <button class="btn" onclick="testEndpoint('GET', '/api/vehicles')">Test Endpoint</button>
                                <div class="response-console" id="vehicles-list-response"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Endpoints -->
                    <div class="endpoint-group" id="adminEndpoints" style="display: none;">
                        <h3><i class="fas fa-cogs"></i> Admin Only</h3>
                        
                        <div class="endpoint">
                            <div class="endpoint-header" onclick="toggleEndpoint('admin-users')">
                                <div>
                                    <span class="endpoint-method method-get">GET</span>
                                    <span>/api/admin/users</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="endpoint-content" id="admin-users">
                                <p>Get all users (Admin only)</p>
                                <button class="btn" onclick="testEndpoint('GET', '/api/admin/users')">Test Endpoint</button>
                                <div class="response-console" id="admin-users-response"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentToken = null;
            let currentUser = null;
            const baseUrl = '{{ url("/api") }}';

            // Chat functionality
            function addMessage(text, isUser = false) {
                const chatMessages = document.getElementById('chatMessages');
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
                messageDiv.innerHTML = `<div class="message-bubble">${text}</div>`;
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function scrollToTester() {
                document.querySelector('.api-tester').scrollIntoView({ behavior: 'smooth' });
                addMessage('Great! Scroll down to start testing our APIs. Login first to get started! 🚀');
            }

            function showDocumentation() {
                window.open('/api/documentation', '_blank');
                addMessage('Opening API documentation in a new tab! 📚');
            }

            function showSystemInfo() {
                addMessage(`📊 System Information:\n\n🔗 Base URL: ${baseUrl}\n🏠 Environment: {{ app()->environment() }}\n🚀 Laravel: {{ app()->version() }}\n📞 PHP: {{ phpversion() }}\n\n🟢 Server Status: Online`);
            }

            function clearChat() {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML = `
                    <div class="message bot">
                        <div class="message-bubble">
                            👋 Welcome! I'm your API testing assistant. Click "Test API Access" below to get started with authentication and explore all endpoints!
                        </div>
                    </div>
                `;
            }

            // Authentication functionality
            async function loginUser() {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const role = document.getElementById('userRoleSelect').value;
                
                if (!email || !password) {
                    addMessage('⚠️ Please enter both email and password!', true);
                    return;
                }

                addMessage(`🔄 Attempting to login as ${role}...`, true);
                
                try {
                    const response = await fetch(`${baseUrl}/login`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email, password })
                    });
                    
                    const data = await response.json();
                    
                    // Handle both 'token' and 'access_token' field names
                    const token = data.token || data.access_token;
                    
                    if (response.ok && token) {
                        currentToken = token;
                        // If user data is not in response, fetch it
                        if (data.user) {
                            currentUser = data.user;
                            showUserInterface(data.user, token);
                        } else {
                            // Fetch user data using the token
                            await fetchUserData(token);
                        }
                        
                        addMessage(`✅ Successfully logged in! You can now test all available endpoints below.`);
                    } else {
                        addMessage(`❌ Login failed: ${data.message || 'Invalid credentials'}`);
                    }
                } catch (error) {
                    addMessage(`❌ Login error: ${error.message}`);
                }
            }

            async function fetchUserData(token) {
                try {
                    const response = await fetch(`${baseUrl}/user`, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const userData = await response.json();
                        currentUser = userData;
                        showUserInterface(userData, token);
                    } else {
                        addMessage('❌ Failed to fetch user data');
                    }
                } catch (error) {
                    addMessage(`❌ Error fetching user data: ${error.message}`);
                }
            }

            function showUserInterface(user, token) {
                // Show user info
                document.getElementById('userName').textContent = user.name;
                document.getElementById('userRole').textContent = user.role;
                document.getElementById('userInfo').classList.add('show');
                
                // Hide auth section and show endpoints
                document.getElementById('authSection').classList.add('hidden');
                document.getElementById('apiEndpoints').classList.remove('hidden');
                
                // Show admin endpoints if user is admin
                if (user.role === 'admin') {
                    document.getElementById('adminEndpoints').style.display = 'block';
                }
                
                // Show token in main display
                document.getElementById('tokenDisplay').innerHTML = `<strong>Access Token:</strong> ${token.substring(0, 50)}...`;
                document.getElementById('tokenDisplay').classList.remove('hidden');
                
                // Update all auth token displays
                const tokenDisplays = document.querySelectorAll('.token-display-auth');
                tokenDisplays.forEach(display => {
                    display.innerHTML = `${token.substring(0, 30)}...${token.substring(token.length - 10)}`;
                });
            }

            function logout() {
                currentToken = null;
                currentUser = null;
                
                // Hide user info and endpoints
                document.getElementById('userInfo').classList.remove('show');
                document.getElementById('apiEndpoints').classList.add('hidden');
                document.getElementById('authSection').classList.remove('hidden');
                document.getElementById('adminEndpoints').style.display = 'none';
                document.getElementById('tokenDisplay').classList.add('hidden');
                
                addMessage('👋 Logged out successfully! Login again to test APIs.');
            }

            // API testing functionality
            function toggleEndpoint(endpointId) {
                const content = document.getElementById(endpointId);
                const icon = content.previousElementSibling.querySelector('.fas');
                
                if (content.classList.contains('show')) {
                    content.classList.remove('show');
                    icon.className = 'fas fa-chevron-down';
                } else {
                    content.classList.add('show');
                    icon.className = 'fas fa-chevron-up';
                }
            }

            async function testEndpoint(method, endpoint) {
                if (!currentToken) {
                    addMessage('⚠️ Please login first to test endpoints!', true);
                    return;
                }

                const responseId = endpoint.replace(/\/api\//g, '').replace(/\//g, '-').replace(/\W/g, '') + '-response';
                const responseConsole = document.getElementById(responseId);
                
                if (!responseConsole) {
                    addMessage('❌ Response console not found for this endpoint!');
                    return;
                }
                
                responseConsole.innerHTML = 'Loading... <div class="loading"></div>';
                
                try {
                    const response = await fetch(`${baseUrl}${endpoint}`, {
                        method: method,
                        headers: {
                            'Authorization': `Bearer ${currentToken}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    const status = response.ok ? '✅ Success' : '❌ Error';
                    const timestamp = new Date().toLocaleTimeString();
                    
                    const formattedResponse = `[${timestamp}] ${status} (${response.status})\n` +
                        `${'='.repeat(50)}\n` +
                        `Endpoint: ${method} ${endpoint}\n` +
                        `Status: ${response.status} ${response.statusText}\n` +
                        `${'='.repeat(50)}\n\n` +
                        `Response Data:\n` +
                        `${JSON.stringify(data, null, 2)}`;
                    
                    responseConsole.innerHTML = formattedResponse;
                    
                    // Add syntax highlighting for JSON
                    if (response.ok) {
                        responseConsole.style.color = '#00ff00';
                    } else {
                        responseConsole.style.color = '#ff6b6b';
                    }
                    
                    addMessage(`📊 Tested ${method} ${endpoint} - Status: ${response.status}`);
                } catch (error) {
                    responseConsole.innerHTML = `❌ Error: ${error.message}`;
                    addMessage(`❌ API test failed: ${error.message}`);
                }
            }

            async function testEndpointWithData(method, endpoint, dataElementId) {
                if (!currentToken) {
                    addMessage('⚠️ Please login first to test endpoints!', true);
                    return;
                }

                const dataElement = document.getElementById(dataElementId);
                const responseId = endpoint.replace(/\/api\//g, '').replace(/\//g, '-').replace(/\W/g, '') + '-response';
                const responseConsole = document.getElementById(responseId);
                
                if (!responseConsole) {
                    addMessage('❌ Response console not found for this endpoint!');
                    return;
                }
                
                let requestData;
                try {
                    requestData = JSON.parse(dataElement.value);
                } catch (e) {
                    responseConsole.innerHTML = '❌ Invalid JSON data';
                    return;
                }
                
                responseConsole.innerHTML = 'Loading... <div class="loading"></div>';
                
                try {
                    const response = await fetch(`${baseUrl}${endpoint}`, {
                        method: method,
                        headers: {
                            'Authorization': `Bearer ${currentToken}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestData)
                    });
                    
                    const data = await response.json();
                    const status = response.ok ? '✅ Success' : '❌ Error';
                    const timestamp = new Date().toLocaleTimeString();
                    
                    const formattedResponse = `[${timestamp}] ${status} (${response.status})\n` +
                        `${'='.repeat(50)}\n` +
                        `Endpoint: ${method} ${endpoint}\n` +
                        `Status: ${response.status} ${response.statusText}\n` +
                        `${'='.repeat(50)}\n\n` +
                        `Request Data:\n${JSON.stringify(requestData, null, 2)}\n\n` +
                        `${'='.repeat(50)}\n\n` +
                        `Response Data:\n${JSON.stringify(data, null, 2)}`;
                    
                    responseConsole.innerHTML = formattedResponse;
                    
                    // Add syntax highlighting
                    if (response.ok) {
                        responseConsole.style.color = '#00ff00';
                    } else {
                        responseConsole.style.color = '#ff6b6b';
                    }
                    
                    addMessage(`📊 Tested ${method} ${endpoint} with data - Status: ${response.status}`);
                } catch (error) {
                    responseConsole.innerHTML = `❌ Error: ${error.message}`;
                    addMessage(`❌ API test failed: ${error.message}`);
                }
            }

            async function testRouteSearch() {
                if (!currentToken) {
                    addMessage('⚠️ Please login first to test endpoints!', true);
                    return;
                }

                const origin = document.getElementById('origin').value;
                const destination = document.getElementById('destination').value;
                const journeyDate = document.getElementById('journey_date').value;
                
                const responseConsole = document.getElementById('routes-search-response');
                responseConsole.innerHTML = 'Loading... <div class="loading"></div>';
                
                const params = new URLSearchParams({
                    origin: origin,
                    destination: destination,
                    journey_date: journeyDate
                });
                
                try {
                    const response = await fetch(`${baseUrl}/routes/search?${params}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${currentToken}`,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    const status = response.ok ? '✅ Success' : '❌ Error';
                    
                    responseConsole.innerHTML = `${status} (${response.status})\n\nParams: ${params.toString()}\n\nResponse:\n${JSON.stringify(data, null, 2)}`;
                    
                    addMessage(`📊 Searched routes from ${origin} to ${destination} - Status: ${response.status}`);
                } catch (error) {
                    responseConsole.innerHTML = `❌ Error: ${error.message}`;
                    addMessage(`❌ Route search failed: ${error.message}`);
                }
            }

            // Terminal control functions
            function clearTerminal(terminalId) {
                const terminal = document.getElementById(terminalId);
                if (terminal) {
                    terminal.innerHTML = 'Terminal cleared. Ready for new API response...';
                    addMessage(`🗑️ Cleared terminal: ${terminalId}`);
                }
            }

            function copyTerminal(terminalId) {
                const terminal = document.getElementById(terminalId);
                if (terminal) {
                    navigator.clipboard.writeText(terminal.textContent).then(() => {
                        addMessage('📋 Terminal content copied to clipboard!');
                        
                        // Visual feedback
                        const copyBtn = event.target;
                        const originalText = copyBtn.textContent;
                        copyBtn.textContent = '✓ Copied';
                        copyBtn.style.background = '#28a745';
                        
                        setTimeout(() => {
                            copyBtn.textContent = originalText;
                            copyBtn.style.background = '#444';
                        }, 2000);
                    }).catch(() => {
                        addMessage('❌ Failed to copy terminal content');
                    });
                }
            }

            function toggleFullscreen(terminalId) {
                const terminal = document.getElementById(terminalId);
                const container = terminal.closest('.terminal-container');
                
                if (container.classList.contains('fullscreen')) {
                    container.classList.remove('fullscreen');
                    addMessage('📏 Terminal exited fullscreen mode');
                } else {
                    container.classList.add('fullscreen');
                    addMessage('🗺 Terminal entered fullscreen mode');
                }
            }

            // Enhanced API Testing Functions
            function switchTab(endpointId, tabName) {
                // Remove active class from all tab headers and contents for this endpoint
                const tabHeaders = document.querySelectorAll(`#${endpointId} .tab-header`);
                const tabContents = document.querySelectorAll(`#${endpointId} .tab-content`);
                
                tabHeaders.forEach(header => header.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked tab
                event.target.classList.add('active');
                document.getElementById(`${endpointId}-${tabName}`).classList.add('active');
                
                addMessage(`🔍 Switched to ${tabName.toUpperCase()} tab for ${endpointId}`);
            }

            function switchBodyType(endpointId, bodyType) {
                // Remove active class from all body type tabs
                const bodyTypeTabs = document.querySelectorAll(`#${endpointId} .body-type-tab`);
                const bodyContents = document.querySelectorAll(`#${endpointId} .body-content`);
                
                bodyTypeTabs.forEach(tab => tab.classList.remove('active'));
                bodyContents.forEach(content => content.style.display = 'none');
                
                // Add active class to clicked tab
                event.target.classList.add('active');
                
                // Show corresponding content
                const contentId = `${endpointId}-${bodyType}`;
                const content = document.getElementById(contentId);
                if (content) {
                    content.style.display = 'block';
                }
                
                addMessage(`🔄 Switched to ${bodyType.toUpperCase()} body type for ${endpointId}`);
            }

            function handleImageUpload(input) {
                const file = input.files[0];
                if (file) {
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    if (!allowedTypes.includes(file.type)) {
                        addMessage('❌ Invalid file type. Please select a valid image file.');
                        return;
                    }
                    
                    // Validate file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        addMessage('❌ File too large. Please select an image under 2MB.');
                        return;
                    }
                    
                    // Display selected file info
                    document.getElementById('file-name').textContent = file.name;
                    document.getElementById('file-size').textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    document.getElementById('selected-image').style.display = 'block';
                    
                    addMessage(`🖼️ Selected image: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`);
                }
            }

            function removeSelectedImage() {
                document.getElementById('profile-image-input').value = '';
                document.getElementById('selected-image').style.display = 'none';
                addMessage('🗑️ Removed selected image');
            }

            async function testProfileUpdate() {
                if (!currentToken) {
                    addMessage('⚠️ Please login first to test profile update!', true);
                    return;
                }

                const responseConsole = document.getElementById('profile-update-response');
                const statusElement = document.getElementById('profile-update-status');
                const sendBtn = event.target;
                
                // Update UI to show testing state
                sendBtn.classList.add('testing');
                sendBtn.innerHTML = '🔄 Sending... <span class="testing-indicator"></span>';
                statusElement.className = 'response-status';
                statusElement.textContent = '🔄 Sending Request...';
                responseConsole.innerHTML = 'Preparing request...\n';
                
                try {
                    // Check which body type is active
                    const activeBodyTab = document.querySelector('#profile-update .body-type-tab.active');
                    const bodyType = activeBodyTab.textContent.toLowerCase();
                    
                    let requestOptions = {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${currentToken}`,
                            'Accept': 'application/json'
                        }
                    };
                    
                    let requestData = {};
                    
                    if (bodyType === 'json') {
                        // JSON request
                        const jsonData = document.getElementById('profile-json-data').value;
                        requestData = JSON.parse(jsonData);
                        requestOptions.headers['Content-Type'] = 'application/json';
                        requestOptions.body = JSON.stringify(requestData);
                        
                    } else if (bodyType === 'form data' || bodyType === 'file upload') {
                        // Form data request
                        const formData = new FormData();
                        
                        // Add form fields
                        const nameField = document.getElementById('form-name');
                        const phoneField = document.getElementById('form-phone');
                        const emailField = document.getElementById('form-email');
                        
                        if (nameField && nameField.value) formData.append('name', nameField.value);
                        if (phoneField && phoneField.value) formData.append('phone_number', phoneField.value);
                        if (emailField && emailField.value) formData.append('email', emailField.value);
                        
                        // Add image if selected
                        const imageInput = document.getElementById('profile-image-input');
                        if (imageInput && imageInput.files[0]) {
                            formData.append('profile_image', imageInput.files[0]);
                        }
                        
                        requestOptions.body = formData;
                        // Don't set Content-Type for FormData, browser will set it with boundary
                    }
                    
                    addMessage(`📡 Sending ${bodyType.toUpperCase()} request to profile update endpoint...`);
                    
                    const response = await fetch(`${baseUrl}/profile`, requestOptions);
                    const data = await response.json();
                    
                    const timestamp = new Date().toLocaleTimeString();
                    const status = response.ok ? '✅ Success' : '❌ Error';
                    
                    let formattedResponse = `[${timestamp}] ${status} (${response.status})\n`;
                    formattedResponse += `${'='.repeat(60)}\n`;
                    formattedResponse += `Endpoint: POST /api/profile\n`;
                    formattedResponse += `Status: ${response.status} ${response.statusText}\n`;
                    formattedResponse += `Body Type: ${bodyType.toUpperCase()}\n`;
                    formattedResponse += `${'='.repeat(60)}\n\n`;
                    
                    if (bodyType === 'json') {
                        formattedResponse += `Request Data:\n${JSON.stringify(requestData, null, 2)}\n\n`;
                    } else {
                        formattedResponse += `Form Data Fields:\n`;
                        if (document.getElementById('form-name').value) formattedResponse += `- name: ${document.getElementById('form-name').value}\n`;
                        if (document.getElementById('form-phone').value) formattedResponse += `- phone_number: ${document.getElementById('form-phone').value}\n`;
                        if (document.getElementById('form-email').value) formattedResponse += `- email: ${document.getElementById('form-email').value}\n`;
                        
                        const imageInput = document.getElementById('profile-image-input');
                        if (imageInput && imageInput.files[0]) {
                            formattedResponse += `- profile_image: ${imageInput.files[0].name} (${(imageInput.files[0].size / 1024 / 1024).toFixed(2)} MB)\n`;
                        }
                        formattedResponse += `\n`;
                    }
                    
                    formattedResponse += `${'='.repeat(60)}\n\n`;
                    formattedResponse += `Response Data:\n${JSON.stringify(data, null, 2)}`;
                    
                    responseConsole.innerHTML = formattedResponse;
                    
                    // Update status and styling
                    if (response.ok) {
                        responseConsole.style.color = '#00ff00';
                        statusElement.className = 'response-status success';
                        statusElement.textContent = `✅ ${response.status} Success`;
                        addMessage(`✅ Profile updated successfully! Status: ${response.status}`);
                    } else {
                        responseConsole.style.color = '#ff6b6b';
                        statusElement.className = 'response-status error';
                        statusElement.textContent = `❌ ${response.status} Error`;
                        addMessage(`❌ Profile update failed! Status: ${response.status}`);
                    }
                    
                } catch (error) {
                    responseConsole.innerHTML = `❌ Error: ${error.message}`;
                    responseConsole.style.color = '#ff6b6b';
                    statusElement.className = 'response-status error';
                    statusElement.textContent = '❌ Request Failed';
                    addMessage(`❌ Profile update error: ${error.message}`);
                } finally {
                    // Reset button state
                    sendBtn.classList.remove('testing');
                    sendBtn.innerHTML = '📡 Send Request';
                }
            }

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                // Add some example credentials for different roles
                document.getElementById('userRoleSelect').addEventListener('change', function() {
                    const role = this.value;
                    const emailField = document.getElementById('email');
                    
                    switch(role) {
                        case 'admin':
                            emailField.value = 'admin@example.com';
                            break;
                        case 'user':
                            emailField.value = 'user@example.com';
                            break;
                        case 'operator':
                            emailField.value = 'operator@example.com';
                            break;
                    }
                });
                
                addMessage('🚀 Welcome! Ready to explore our Ticket Booking API? Start by clicking "Test API Access" to login and test all endpoints!');
            });
        </script>
    </body>
</html>