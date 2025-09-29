

<x-layout>
    <x-slot name="scripts">
        <style>
            /* Profile Page Override Styles - Higher Specificity to Override Tailwind */
            .profile-page-wrapper * {
                box-sizing: border-box;
            }
            
            .profile-page-wrapper .settings-grid {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
                gap: 2rem !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .profile-page-wrapper .settings-card {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
                border: 2px solid #e2e8f0 !important;
                border-radius: 1rem !important;
                padding: 2rem !important;
                transition: all 0.3s ease !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                margin: 0 !important;
                width: 100% !important;
                position: relative !important;
            }

            .profile-page-wrapper .settings-card:hover {
                border-color: #3B82F6 !important;
                transform: translateY(-4px) !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            }

            .profile-page-wrapper .settings-title {
                font-weight: 700 !important;
                font-size: 1.25rem !important;
                color: #1F2937 !important;
                margin: 0 0 1.5rem 0 !important;
                display: flex !important;
                align-items: center !important;
                padding: 0 0 0.75rem 0 !important;
                border-bottom: 2px solid #e2e8f0 !important;
            }

            .profile-page-wrapper .settings-options {
                display: flex !important;
                flex-direction: column !important;
                gap: 1rem !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .profile-page-wrapper .info-item {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 0.75rem 0 !important;
                border-bottom: 1px solid #e5e7eb !important;
                margin: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .profile-page-wrapper .info-item:last-child {
                border-bottom: none !important;
            }

            .profile-page-wrapper .info-label {
                font-weight: 600 !important;
                color: #6b7280 !important;
                font-size: 0.875rem !important;
                margin: 0 !important;
                flex-shrink: 0 !important;
            }

            .profile-page-wrapper .info-value {
                font-weight: 500 !important;
                color: #1f2937 !important;
                background: #f3f4f6 !important;
                padding: 0.25rem 0.75rem !important;
                border-radius: 0.5rem !important;
                font-size: 0.875rem !important;
                margin: 0 !important;
                text-align: right !important;
                flex-shrink: 0 !important;
            }

            .profile-page-wrapper .change-password-btn {
                background: linear-gradient(135deg, #10B981, #059669) !important;
                color: white !important;
                border: none !important;
                padding: 1rem 2rem !important;
                border-radius: 0.75rem !important;
                font-weight: 600 !important;
                cursor: pointer !important;
                transition: all 0.3s ease !important;
                display: flex !important;
                align-items: center !important;
                gap: 0.75rem !important;
                width: fit-content !important;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3) !important;
                margin: 0 !important;
                text-decoration: none !important;
                font-size: 1rem !important;
            }

            .profile-page-wrapper .change-password-btn:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4) !important;
            }

            /* My Account Page Specific Styles */
            .profile-content {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
                align-items: start;
            }
            
            @media (min-width: 768px) {
                .profile-content {
                    grid-template-columns: 1fr 2fr;
                    gap: 3rem;
                }
            }

            .profile-photo-section {
                text-align: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 1.5rem;
                border-radius: 1.5rem;
                color: white;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }
            
            @media (min-width: 640px) {
                .profile-photo-section {
                    padding: 2rem;
                }
            }

            .photo-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1.5rem;
            }

            .profile-photo {
                position: relative;
                width: 120px;
                height: 120px;
                border-radius: 50%;
                overflow: hidden;
                border: 4px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
                transition: all 0.3s ease;
                margin: 0 auto;
            }
            
            @media (min-width: 640px) {
                .profile-photo {
                    width: 150px;
                    height: 150px;
                }
            }
            
            @media (min-width: 768px) {
                .profile-photo {
                    width: 180px;
                    height: 180px;
                    border: 5px solid rgba(255, 255, 255, 0.3);
                }
            }

            .profile-photo:hover {
                transform: scale(1.05);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            }

            .profile-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .photo-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
            }

            .profile-photo:hover .photo-overlay {
                opacity: 1;
            }

            .change-photo-btn {
                background: linear-gradient(135deg, #2c41dd 0%, #000b5b 100%);
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 2rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
                position: relative;
                overflow: hidden;
                font-size: 0.875rem;
            }
            
            @media (min-width: 640px) {
                .change-photo-btn {
                    padding: 1rem 2rem;
                    gap: 0.75rem;
                    font-size: 1rem;
                }
            }

            .change-photo-btn:before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .change-photo-btn:hover:before {
                left: 100%;
            }

            .change-photo-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
            }

            .profile-form {
                flex: 1;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 1.5rem;
                border-radius: 1.5rem;
                border: 2px solid #e2e8f0;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }
            
            @media (min-width: 640px) {
                .profile-form {
                    padding: 2rem;
                }
            }

            .form-row {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            
            @media (min-width: 768px) {
                .form-row {
                    grid-template-columns: 1fr 1fr;
                    gap: 2rem;
                    margin-bottom: 2rem;
                }
            }

            .form-group {
                display: flex;
                flex-direction: column;
                position: relative;
            }

            .form-label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.75rem;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .form-input {
                padding: 1rem;
                border: 2px solid #E5E7EB;
                border-radius: 0.75rem;
                font-size: 1rem;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
            }

            .form-input:focus {
                outline: none;
                border-color: #3B82F6;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
                background: white;
                transform: translateY(-2px);
            }

            .form-input.readonly {
                background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
                color: #6B7280;
                cursor: not-allowed;
            }

            .field-note {
                color: #6B7280;
                font-size: 0.75rem;
                margin-top: 0.25rem;
            }

            .change-password-btn {
                background: linear-gradient(135deg, #10B981, #059669) !important;
                color: white !important;
                border: none !important;
                padding: 1rem 2rem !important;
                border-radius: 0.75rem !important;
                font-weight: 600 !important;
                cursor: pointer !important;
                transition: all 0.3s ease !important;
                display: flex !important;
                align-items: center !important;
                gap: 0.75rem !important;
                width: fit-content !important;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3) !important;
                margin: 0 !important;
                text-decoration: none !important;
                font-size: 1rem !important;
            }

            .change-password-btn:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4) !important;
            }

            .form-actions {
                margin-top: 2rem;
                text-align: right;
            }

            .save-btn {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                padding: 1rem 2rem;
                border-radius: 2rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 1rem;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
                position: relative;
                overflow: hidden;
            }
            
            @media (min-width: 640px) {
                .save-btn {
                    padding: 1.25rem 3rem;
                    gap: 0.75rem;
                    font-size: 1.1rem;
                }
            }

            .save-btn:before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .save-btn:hover:before {
                left: 100%;
            }

            .save-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            }

            .settings-grid {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
                gap: 2rem !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .settings-card {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
                border: 2px solid #e2e8f0 !important;
                border-radius: 1rem !important;
                padding: 2rem !important;
                transition: all 0.3s ease !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                margin: 0 !important;
                width: 100% !important;
                position: relative !important;
            }

            .settings-card:hover {
                border-color: #3B82F6 !important;
                transform: translateY(-4px) !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            }

            .settings-title {
                font-weight: 700 !important;
                font-size: 1.25rem !important;
                color: #1F2937 !important;
                margin: 0 0 1.5rem 0 !important;
                display: flex !important;
                align-items: center !important;
                padding: 0 0 0.75rem 0 !important;
                border-bottom: 2px solid #e2e8f0 !important;
            }

            .settings-options {
                display: flex !important;
                flex-direction: column !important;
                gap: 1rem !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .info-item {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 0.75rem 0 !important;
                border-bottom: 1px solid #e5e7eb !important;
                margin: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .info-item:last-child {
                border-bottom: none !important;
            }

            .info-label {
                font-weight: 600 !important;
                color: #6b7280 !important;
                font-size: 0.875rem !important;
                margin: 0 !important;
                flex-shrink: 0 !important;
            }

            .info-value {
                font-weight: 500 !important;
                color: #1f2937 !important;
                background: #f3f4f6 !important;
                padding: 0.25rem 0.75rem !important;
                border-radius: 0.5rem !important;
                font-size: 0.875rem !important;
                margin: 0 !important;
                text-align: right !important;
                flex-shrink: 0 !important;
            }

            .setting-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .setting-label {
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                width: 100%;
                font-weight: 500;
                color: #374151;
            }

            .setting-toggle {
                appearance: none;
                width: 3rem;
                height: 1.5rem;
                background: #D1D5DB;
                border-radius: 0.75rem;
                position: relative;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .setting-toggle:checked {
                background: #3B82F6;
            }

            .toggle-slider {
                position: absolute;
                top: 0.125rem;
                left: 0.125rem;
                width: 1.25rem;
                height: 1.25rem;
                background: white;
                border-radius: 50%;
                transition: transform 0.3s ease;
                pointer-events: none;
            }

            .setting-toggle:checked + .toggle-slider {
                transform: translateX(1.5rem);
            }

            .theme-options {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .theme-option {
                cursor: pointer;
            }

            .theme-preview {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem;
                border: 2px solid #E5E7EB;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                background: white;
            }

            .theme-option input:checked + .theme-preview {
                border-color: #3B82F6;
                background: #EFF6FF;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .language-dropdown {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid #E5E7EB;
                border-radius: 0.5rem;
                background: white;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .language-dropdown:focus {
                outline: none;
                border-color: #3B82F6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .language-note {
                color: #6B7280;
                font-size: 0.75rem;
                margin-top: 0.5rem;
                display: flex;
                align-items: center;
            }

            .help-options {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
            }

            .help-btn {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1.5rem;
                border: none;
                border-radius: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: left;
                width: 100%;
            }

            .help-btn.primary {
                background: linear-gradient(135deg, #3B82F6, #2563eb);
                color: white;
            }

            .help-btn.secondary {
                background: linear-gradient(135deg, #10B981, #059669);
                color: white;
            }

            .help-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .help-btn-icon {
                font-size: 1.5rem;
                width: 3rem;
                height: 3rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
            }

            .help-btn-content h3 {
                font-weight: 700;
                font-size: 1.125rem;
                margin-bottom: 0.25rem;
            }

            .help-btn-content p {
                opacity: 0.9;
                font-size: 0.875rem;
            }

            .logout-btn {
                background: linear-gradient(135deg, #EF4444, #DC2626);
                color: white;
                border: none;
                padding: 1rem 2rem;
                border-radius: 0.5rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 1rem;
            }

            .logout-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            }

            .modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                backdrop-filter: blur(10px);
                animation: modalBackdropFadeIn 0.3s ease-out;
            }

            @keyframes modalBackdropFadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .modal.hidden {
                display: none;
            }

            .modal-content {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 1.5rem;
                max-width: 600px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.2);
                animation: modalSlideIn 0.3s ease-out;
            }

            @keyframes modalSlideIn {
                from { 
                    opacity: 0;
                    transform: translateY(-20px) scale(0.95);
                }
                to { 
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 2rem;
                border-bottom: 2px solid #e2e8f0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 1.5rem 1.5rem 0 0;
            }

            .modal-header h3 {
                font-weight: 700;
                font-size: 1.5rem;
                color: white;
                display: flex;
                align-items: center;
                margin: 0;
            }

            .modal-close {
                background: rgba(255, 255, 255, 0.2);
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: white;
                transition: all 0.3s ease;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-close:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(90deg);
            }

            .modal-body {
                padding: 2rem;
            }

            .modal-actions {
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                padding: 1.5rem 2rem;
                border-top: 2px solid #e2e8f0;
                background: #f8fafc;
                border-radius: 0 0 1.5rem 1.5rem;
            }

            .btn-cancel, .btn-save, .btn-update, .btn-send, .btn-confirm, .btn-primary {
                padding: 1rem 2rem;
                border: none;
                border-radius: 0.75rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                font-size: 0.95rem;
                position: relative;
                overflow: hidden;
            }

            .btn-cancel {
                background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
                color: #374151;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .btn-cancel:hover {
                background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            .btn-save, .btn-update, .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            }

            .btn-send {
                background: linear-gradient(135deg, #10B981, #059669);
                color: white;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            }

            .btn-confirm {
                background: linear-gradient(135deg, #EF4444, #DC2626);
                color: white;
                box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            }

            .btn-save:before, .btn-update:before, .btn-send:before, .btn-confirm:before, .btn-primary:before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-save:hover:before, .btn-update:hover:before, .btn-send:hover:before, .btn-confirm:hover:before, .btn-primary:hover:before {
                left: 100%;
            }

            .btn-save:hover, .btn-update:hover, .btn-send:hover, .btn-confirm:hover, .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            /* Enhanced Password Change Modal Styles */
            .password-field {
                position: relative;
                display: flex;
                align-items: center;
            }

            .password-field input[type="password"],
            .password-field input[type="text"] {
                flex: 1;
                padding-right: 3rem;
                border: 2px solid #e2e8f0;
                border-radius: 0.75rem;
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
                transition: all 0.3s ease;
                background: #fafafa;
            }

            .password-field input:focus {
                outline: none;
                border-color: #3B82F6;
                background: white;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .password-toggle {
                position: absolute;
                right: 0.75rem;
                background: none;
                border: none;
                color: #6b7280;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 0.375rem;
                transition: all 0.2s ease;
                z-index: 10;
            }

            .password-toggle:hover {
                color: #3B82F6;
                background: rgba(59, 130, 246, 0.1);
            }

            .password-strength {
                margin-top: 0.75rem;
                padding: 0.75rem;
                background: #f8fafc;
                border-radius: 0.5rem;
                border: 1px solid #e2e8f0;
            }

            .strength-bar {
                width: 100%;
                height: 8px;
                background: #e2e8f0;
                border-radius: 4px;
                overflow: hidden;
                margin-bottom: 0.5rem;
            }

            .strength-fill {
                height: 100%;
                width: 0%;
                transition: all 0.3s ease;
                border-radius: 4px;
            }

            .strength-fill.weak {
                width: 25%;
                background: linear-gradient(90deg, #ef4444, #f87171);
            }

            .strength-fill.fair {
                width: 50%;
                background: linear-gradient(90deg, #f59e0b, #fbbf24);
            }

            .strength-fill.good {
                width: 75%;
                background: linear-gradient(90deg, #3b82f6, #60a5fa);
            }

            .strength-fill.strong {
                width: 100%;
                background: linear-gradient(90deg, #10b981, #34d399);
            }

            .strength-text {
                font-size: 0.75rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .strength-text.weak {
                color: #ef4444;
            }

            .strength-text.fair {
                color: #f59e0b;
            }

            .strength-text.good {
                color: #3b82f6;
            }

            .strength-text.strong {
                color: #10b981;
            }

            .password-match {
                margin-top: 0.5rem;
                padding: 0.5rem 0.75rem;
                border-radius: 0.5rem;
                font-size: 0.75rem;
                font-weight: 600;
                display: none;
            }

            .password-match.match {
                display: block;
                background: #dcfce7;
                color: #16a34a;
                border: 1px solid #bbf7d0;
            }

            .password-match.no-match {
                display: block;
                background: #fee2e2;
                color: #dc2626;
                border: 1px solid #fecaca;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
            }

            .password-requirements {
                margin-top: 0.75rem;
                padding: 0.75rem;
                background: #f8fafc;
                border-radius: 0.5rem;
                border: 1px solid #e2e8f0;
            }

            .password-requirements h4 {
                font-size: 0.75rem;
                font-weight: 600;
                color: #374151;
                margin: 0 0 0.5rem 0;
            }

            .password-requirements ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .password-requirements li {
                font-size: 0.75rem;
                color: #6b7280;
                margin-bottom: 0.25rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .password-requirements li i {
                width: 12px;
                color: #9ca3af;
            }

            .password-requirements li.valid {
                color: #16a34a;
            }

            .password-requirements li.valid i {
                color: #16a34a;
            }

            .form-error {
                margin-top: 0.5rem;
                padding: 0.5rem 0.75rem;
                background: #fee2e2;
                color: #dc2626;
                border: 1px solid #fecaca;
                border-radius: 0.5rem;
                font-size: 0.75rem;
                display: none;
            }

            .form-error.show {
                display: block;
            }

            /* Loading state for buttons */
            .btn-loading {
                position: relative;
                color: transparent !important;
            }

            .btn-loading:before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 16px;
                height: 16px;
                border: 2px solid transparent;
                border-top: 2px solid currentColor;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                color: white;
            }

            @keyframes spin {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .profile-content {
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                }

                .form-row {
                    grid-template-columns: 1fr;
                }

                .settings-grid {
                    grid-template-columns: 1fr;
                }

                .help-options {
                    grid-template-columns: 1fr;
                }

                .modal-content {
                    width: 95%;
                    margin: 1rem;
                }
            }

            /* Success message animation */
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            #successMessage {
                animation: slideIn 0.3s ease-out;
            }

            #successMessage.hide {
                animation: slideOut 0.3s ease-in;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Profile form save functionality
                document.getElementById('saveProfileBtn')?.addEventListener('click', function() {
                    // Form will submit normally via HTML form action
                });

                // Change photo modal
                document.getElementById('changePhotoBtn')?.addEventListener('click', function() {
                    document.getElementById('photoUploadModal').classList.remove('hidden');
                });

                document.body.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'deletePhotoBtn') {
                        if (confirm('Are you sure you want to delete your profile photo?')) {
                            const deleteBtn = e.target;
                            deleteBtn.disabled = true;
                            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...';

                            fetch('{{ route("instructor.deleteProfileImage") }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const photoContainer = document.getElementById('profilePhotoContainer');
                                    // Replace image with the user's initial
                                    photoContainer.innerHTML = `
                                        <div class="profile-img bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                                            ${data.user_initial}
                                        </div>
                                    `;
                                    // Remove the delete button from the page
                                    deleteBtn.remove();
                                    showSuccessMessage('Profile photo deleted successfully!');
                                } else {
                                    alert(data.message || 'An error occurred.');
                                    deleteBtn.disabled = false;
                                    deleteBtn.innerHTML = '<i class="fas fa-trash-alt mr-2"></i> Delete';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
                                deleteBtn.disabled = false;
                                deleteBtn.innerHTML = '<i class="fas fa-trash-alt mr-2"></i> Delete';
                            });
                        }
                    }
                });
                // Change password modal
                document.getElementById('changePasswordBtn')?.addEventListener('click', function() {
                    document.getElementById('passwordChangeModal').classList.remove('hidden');
                });

                // Support modal
                document.getElementById('contactSupportBtn')?.addEventListener('click', function() {
                    document.getElementById('supportModal').classList.remove('hidden');
                });

                // Logout confirmation
                document.getElementById('instructorLogoutBtn')?.addEventListener('click', function() {
                    document.getElementById('logoutConfirmModal').classList.remove('hidden');
                });

                document.getElementById('confirmLogoutBtn')?.addEventListener('click', function() {
                    document.getElementById('instructor-logout-form').submit();
                });

                // Modal close functionality
                document.querySelectorAll('.modal-close, .btn-cancel, #cancelPhotoBtn, #cancelPasswordBtn, #cancelSupportBtn, #cancelLogoutBtn').forEach(function(element) {
                    element.addEventListener('click', function() {
                        element.closest('.modal').classList.add('hidden');
                    });
                });

                // Close modals when clicking outside
                document.querySelectorAll('.modal').forEach(function(modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });

                // Password visibility toggle
                document.querySelectorAll('.password-toggle').forEach(function(toggle) {
                    toggle.addEventListener('click', function() {
                        const targetId = this.dataset.target;
                        const input = document.getElementById(targetId);
                        const icon = this.querySelector('i');

                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                });

                // Enhanced password functionality
                const newPasswordInput = document.getElementById('newPassword');
                const confirmPasswordInput = document.getElementById('confirmPassword');
                const strengthFill = document.getElementById('strengthFill');
                const strengthText = document.getElementById('strengthText');
                const passwordMatch = document.getElementById('passwordMatch');
                const updatePasswordBtn = document.getElementById('updatePasswordBtn');

                // Password strength checker
                function checkPasswordStrength(password) {
                    let strength = 0;
                    const requirements = {
                        length: password.length >= 8,
                        uppercase: /[A-Z]/.test(password),
                        lowercase: /[a-z]/.test(password),
                        number: /\d/.test(password),
                        special: /[@$!%*?&]/.test(password)
                    };

                    // Update requirement indicators
                    Object.keys(requirements).forEach(req => {
                        const element = document.getElementById(`req-${req}`);
                        const icon = element.querySelector('i');
                        if (requirements[req]) {
                            element.classList.add('valid');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-check');
                            strength++;
                        } else {
                            element.classList.remove('valid');
                            icon.classList.remove('fa-check');
                            icon.classList.add('fa-times');
                        }
                    });

                    // Update strength bar and text
                    let strengthLevel = 'weak';
                    let strengthIcon = 'fa-shield-alt';
                    
                    if (strength === 5) {
                        strengthLevel = 'strong';
                        strengthIcon = 'fa-shield-check';
                    } else if (strength >= 4) {
                        strengthLevel = 'good';
                        strengthIcon = 'fa-shield-virus';
                    } else if (strength >= 2) {
                        strengthLevel = 'fair';
                        strengthIcon = 'fa-shield-halved';
                    }

                    strengthFill.className = `strength-fill ${strengthLevel}`;
                    strengthText.className = `strength-text ${strengthLevel}`;
                    strengthText.innerHTML = `<i class="fas ${strengthIcon}"></i> Password strength: ${strengthLevel.charAt(0).toUpperCase() + strengthLevel.slice(1)}`;

                    return strength;
                }

                // Password confirmation checker
                function checkPasswordMatch() {
                    const newPassword = newPasswordInput.value;
                    const confirmPassword = confirmPasswordInput.value;

                    if (confirmPassword.length === 0) {
                        passwordMatch.className = 'password-match';
                        passwordMatch.style.display = 'none';
                        return false;
                    }

                    if (newPassword === confirmPassword) {
                        passwordMatch.className = 'password-match match';
                        passwordMatch.innerHTML = '<i class="fas fa-check mr-1"></i> Passwords match';
                        return true;
                    } else {
                        passwordMatch.className = 'password-match no-match';
                        passwordMatch.innerHTML = '<i class="fas fa-times mr-1"></i> Passwords do not match';
                        return false;
                    }
                }

                // Real-time validation
                if (newPasswordInput) {
                    newPasswordInput.addEventListener('input', function() {
                        if (this.value.length > 0) {
                            checkPasswordStrength(this.value);
                        } else {
                            strengthFill.className = 'strength-fill';
                            strengthText.innerHTML = '<i class="fas fa-shield-alt"></i> Password strength: Enter a password';
                            
                            // Reset requirements
                            document.querySelectorAll('.password-requirements li').forEach(li => {
                                li.classList.remove('valid');
                                const icon = li.querySelector('i');
                                icon.classList.remove('fa-check');
                                icon.classList.add('fa-times');
                            });
                        }
                        checkPasswordMatch();
                    });
                }

                if (confirmPasswordInput) {
                    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
                }

                // Form submission
                if (updatePasswordBtn) {
                    updatePasswordBtn.addEventListener('click', function() {
                        const form = document.getElementById('passwordChangeForm');
                        const formData = new FormData(form);

                        // Clear previous errors
                        document.querySelectorAll('.form-error').forEach(error => {
                            error.classList.remove('show');
                            error.textContent = '';
                        });

                        // Basic validation
                        const currentPassword = document.getElementById('currentPassword').value;
                        const newPassword = newPasswordInput.value;
                        const confirmPassword = confirmPasswordInput.value;

                        if (!currentPassword) {
                            showFieldError('currentPasswordError', 'Current password is required');
                            return;
                        }

                        if (!newPassword) {
                            showFieldError('newPasswordError', 'New password is required');
                            return;
                        }

                        if (checkPasswordStrength(newPassword) < 5) {
                            showFieldError('newPasswordError', 'Password must meet all requirements');
                            return;
                        }

                        if (!checkPasswordMatch()) {
                            showFieldError('confirmPasswordError', 'Passwords do not match');
                            return;
                        }

                        // Set loading state
                        this.disabled = true;
                        this.classList.add('btn-loading');
                        const originalText = this.innerHTML;

                        // Submit form via AJAX
                        fetch('{{ route("instructor.changePassword") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reset form and close modal
                                form.reset();
                                strengthFill.className = 'strength-fill';
                                strengthText.innerHTML = '<i class="fas fa-shield-alt"></i> Password strength: Enter a password';
                                passwordMatch.style.display = 'none';
                                
                                // Reset requirement indicators
                                document.querySelectorAll('.password-requirements li').forEach(li => {
                                    li.classList.remove('valid');
                                    const icon = li.querySelector('i');
                                    icon.classList.remove('fa-check');
                                    icon.classList.add('fa-times');
                                });

                                document.getElementById('passwordChangeModal').classList.add('hidden');
                                showSuccessMessage(data.message);
                            } else {
                                // Handle validation errors
                                if (data.errors) {
                                    Object.keys(data.errors).forEach(field => {
                                        const errorElement = document.getElementById(field.replace('_', '') + 'Error');
                                        if (errorElement) {
                                            showFieldError(errorElement.id, data.errors[field][0]);
                                        }
                                    });
                                } else {
                                    showFieldError('currentPasswordError', data.message || 'An error occurred');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showFieldError('currentPasswordError', 'An error occurred. Please try again.');
                        })
                        .finally(() => {
                            // Reset button state
                            this.disabled = false;
                            this.classList.remove('btn-loading');
                            this.innerHTML = originalText;
                        });
                    });
                }

                function showFieldError(fieldId, message) {
                    const errorElement = document.getElementById(fieldId);
                    if (errorElement) {
                        errorElement.textContent = message;
                        errorElement.classList.add('show');
                    }
                }

                // Photo upload functionality
                const uploadArea = document.getElementById('uploadArea');
                const photoInput = document.getElementById('photoInput');
                const previewImage = document.getElementById('previewImage');
                const photoPreview = document.getElementById('photoPreview');
                const savePhotoBtn = document.getElementById('savePhotoBtn');

                if (uploadArea && photoInput) {
                    uploadArea.addEventListener('click', () => photoInput.click());
                    
                    uploadArea.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.style.borderColor = '#667eea';
                        this.style.backgroundColor = '#f0f4ff';
                    });

                    uploadArea.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        this.style.borderColor = '#d1d5db';
                        this.style.backgroundColor = 'transparent';
                    });

                    uploadArea.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.style.borderColor = '#d1d5db';
                        this.style.backgroundColor = 'transparent';
                        
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            photoInput.files = files;
                            handleFileSelect(files[0]);
                        }
                    });
                    
                    photoInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            handleFileSelect(file);
                        }
                    });
                }

                function handleFileSelect(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        photoPreview.classList.remove('hidden');
                        uploadArea.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }

                if (savePhotoBtn) {
                    savePhotoBtn.addEventListener('click', function() {
                        const formData = new FormData();
                        const fileInput = document.getElementById('photoInput');
                        
                        if (fileInput.files.length > 0) {
                            formData.append('profile_image', fileInput.files[0]);
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                            
                            // Show loading state
                            savePhotoBtn.disabled = true;
                            savePhotoBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...';
                            
                            fetch('{{ route("instructor.uploadProfileImage") }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update profile image on page
                                    const profileImg = document.getElementById('profileImg');
                                    if (profileImg) {
                                        profileImg.src = data.image_url;
                                    } else {
                                        // If no image element exists, create one
                                        const photoContainer = document.getElementById('profilePhotoContainer');
                                        if (photoContainer) {
                                            photoContainer.innerHTML = `<img src="${data.image_url}" alt="Profile Photo" class="profile-img" id="profileImg">`;
                                        }
                                    }
                                    
                                    // Close modal
                                    document.getElementById('photoUploadModal').classList.add('hidden');
                                    
                                    // Show success message
                                    showSuccessMessage('Profile photo updated successfully!');
                                    
                                    // Reset form
                                    document.getElementById('photoUploadForm').reset();
                                    photoPreview.classList.add('hidden');
                                    uploadArea.style.display = 'block';
                                } else {
                                    alert('Error uploading photo: ' + (data.message || 'Unknown error'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error uploading photo. Please try again.');
                            })
                            .finally(() => {
                                // Reset button state
                                savePhotoBtn.disabled = false;
                                savePhotoBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Save Photo';
                            });
                        } else {
                            alert('Please select a photo first.');
                        }
                    });
                }
            });

            function showSuccessMessage(message) {
                const successMsg = document.getElementById('successMessage');
                const successText = document.getElementById('successText');
                
                if (successMsg && successText) {
                    successText.textContent = message;
                    successMsg.classList.remove('hidden');
                    
                    setTimeout(function() {
                        successMsg.classList.add('hide');
                        setTimeout(function() {
                            successMsg.classList.add('hidden');
                            successMsg.classList.remove('hide');
                        }, 300);
                    }, 3000);
                }
            }
        </script>
    </x-slot>

    <!-- Instructor Account Management Page -->
    <div class="profile-page-wrapper">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">My Account</h1>
        <p class="text-gray-600 text-base sm:text-lg">Manage your profile, preferences, and account settings.</p>
    </div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Success Message Notification -->
<div id="successMessage" class="hidden fixed top-6 right-6 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300">
    <i class="fas fa-check-circle mr-2"></i>
    <span id="successText">Profile updated successfully!</span>
</div>

<!-- A. MY PROFILE SECTION -->
<section class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 border border-gray-200">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center">
        <i class="fas fa-user-circle text-blue-600 mr-2 sm:mr-3"></i>
        My Profile
    </h2>
    
    <div class="profile-content">
        <!-- Profile Photo Section -->
        <div class="profile-photo-section">
            <div class="photo-container">
                <div class="profile-photo" id="profilePhotoContainer">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile Photo" class="profile-img" id="profileImg">
                    @else
                        <div class="profile-img bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="photo-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mt-4">{{ Auth::user()->name }}</h3>
                <p class="text-white-600">{{ Auth::user()->title ?? 'Instructor' }} • {{ Auth::user()->department ?? 'OLIN System' }}</p>
                <p class="text-sm text-white-500 mt-2">Member since {{ Auth::user()->created_at->format('F Y') }}</p>
                <div class="flex items-center justify-center space-x-4 mt-4">
                    <button id="changePhotoBtn" class="change-photo-btn">
                        <i class="fas fa-camera mr-2"></i>
                        Change Photo
                    </button>

                    {{-- This button only appears if a profile image exists --}}
                    @if(Auth::user()->profile_image)
                        <button id="deletePhotoBtn" class="change-photo-btn" style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Information Form -->
        <div class="profile-form">
            <form id="profileForm" action="{{ route('instructor.updateProfile') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ Auth::user()->name }}" required>
                        <div class="field-validation" id="nameValidation"></div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input readonly" value="{{ Auth::user()->email }}" readonly>
                        <small class="field-note">Contact admin to change your email address</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="title" class="form-label">Title/Position</label>
                        <select id="title" name="title" class="form-input">
                            <option value="">Select Title</option>
                            <option value="Instructor" {{ Auth::user()->title == 'Instructor' ? 'selected' : '' }}>Instructor</option>
                            <option value="Assistant Professor" {{ Auth::user()->title == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                            <option value="Associate Professor" {{ Auth::user()->title == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                            <option value="Professor" {{ Auth::user()->title == 'Professor' ? 'selected' : '' }}>Professor</option>
                            <option value="Lecturer" {{ Auth::user()->title == 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                            <option value="Senior Lecturer" {{ Auth::user()->title == 'Senior Lecturer' ? 'selected' : '' }}>Senior Lecturer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" id="department" name="department" class="form-input" value="{{ Auth::user()->department }}" placeholder="e.g., Computer Science">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-input" value="{{ Auth::user()->phone }}" placeholder="+1 (555) 123-4567">
                    </div>

                    <div class="form-group">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" id="birth_date" name="birth_date" class="form-input" value="{{ Auth::user()->birth_date ? Auth::user()->birth_date->format('Y-m-d') : '' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-input">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ Auth::user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ Auth::user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ Auth::user()->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            <option value="Prefer not to say" {{ Auth::user()->gender == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" id="role" name="role" class="form-input readonly" value="{{ ucfirst(Auth::user()->role) }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Biography</label>
                    <textarea id="bio" name="bio" class="form-input" rows="4" placeholder="Tell us about yourself, your expertise, and teaching philosophy...">{{ Auth::user()->bio }}</textarea>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-input" rows="3" placeholder="Your current address...">{{ Auth::user()->address }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" id="saveProfileBtn" class="save-btn">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- B. SETTINGS SECTION -->
<section class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 border border-gray-200">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center">
        <i class="fas fa-cog text-blue-600 mr-2 sm:mr-3"></i>
        Account Settings
    </h2>

    <div class="settings-grid">
        <!-- Password Change -->
        <div class="settings-card">
            <h3 class="settings-title">
                <i class="fas fa-lock text-green-500 mr-2"></i>
                Security
            </h3>
            <div class="settings-options">
                <p class="text-gray-600 mb-4">Keep your account secure by regularly updating your password.</p>
                <button type="button" id="changePasswordBtn" class="change-password-btn">
                    <i class="fas fa-key mr-2"></i>
                    Change Password
                </button>
            </div>
        </div>

        <!-- Account Information -->
        <div class="settings-card">
            <h3 class="settings-title">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Account Information
            </h3>
            <div class="settings-options">
                <div class="info-item">
                    <span class="info-label">Account Type:</span>
                    <span class="info-value">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value">{{ Auth::user()->created_at->format('F j, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ Auth::user()->updated_at->format('F j, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- C. HELP SECTION -->
<section class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 border border-gray-200">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center">
        <i class="fas fa-question-circle text-blue-600 mr-2 sm:mr-3"></i>
        Help & Support
    </h2>
    
    <div class="help-options">
        <button id="instructorFaqsBtn" class="help-btn primary">
            <div class="help-btn-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="help-btn-content">
                <h3>Frequently Asked Questions</h3>
                <p>Find answers to common instructor questions</p>
            </div>
        </button>

        <button id="contactSupportBtn" class="help-btn secondary">
            <div class="help-btn-icon">
                <i class="fas fa-headset"></i>
            </div>
            <div class="help-btn-content">
                <h3>Contact Support</h3>
                <p>Get help from our technical support team</p>
            </div>
        </button>
    </div>
</section>

<!-- D. LOG OUT SECTION -->
<section class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 border border-red-200 bg-gradient-to-r from-red-50 to-orange-50">
    <h2 class="text-xl sm:text-2xl font-bold text-red-700 mb-3 sm:mb-4 flex items-center">
        <i class="fas fa-sign-out-alt text-red-600 mr-2 sm:mr-3"></i>
        Account Logout
    </h2>
    <p class="text-gray-700 mb-4 sm:mb-6 text-sm sm:text-base">Securely log out from your instructor account when you're done working.</p>
    
    <form id="instructor-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <button id="instructorLogoutBtn" class="logout-btn">
        <i class="fas fa-sign-out-alt mr-2"></i>
        Log Out
    </button>
</section>

<!-- PHOTO UPLOAD MODAL -->
<div id="photoUploadModal" class="modal hidden">
    <div class="modal-content photo-modal">
        <div class="modal-header">
            <h3><i class="fas fa-camera mr-2"></i>Change Profile Photo</h3>
            <button class="modal-close" id="closePhotoModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="photoUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="upload-area" id="uploadArea" style="border: 2px dashed #d1d5db; border-radius: 1rem; padding: 3rem; text-align: center; cursor: pointer; transition: all 0.3s ease;">
                    <input type="file" id="photoInput" name="profile_image" accept="image/*" hidden>
                    <div class="upload-placeholder">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-700 mb-2">Click to upload or drag and drop</p>
                        <small class="text-gray-500">Supported formats: JPG, PNG, GIF (Max 10MB)</small>
                    </div>
                </div>
                <div class="photo-preview hidden" id="photoPreview" style="margin-top: 1.5rem; text-align: center;">
                    <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 1rem; box-shadow: 0 8px 15px rgba(0,0,0,0.1);">
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" id="cancelPhotoBtn">Cancel</button>
            <button type="button" class="btn-save" id="savePhotoBtn">
                <i class="fas fa-save mr-1"></i> Save Photo
            </button>
        </div>
    </div>
</div>

<!-- PASSWORD CHANGE MODAL -->
<div id="passwordChangeModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-key mr-2"></i>Change Password</h3>
            <button class="modal-close" id="closePasswordModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="passwordChangeForm">
                @csrf
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <div class="password-field">
                        <input type="password" id="currentPassword" name="current_password" required>
                        <button type="button" class="password-toggle" data-target="currentPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-error" id="currentPasswordError"></div>
                </div>
                
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <div class="password-field">
                        <input type="password" id="newPassword" name="new_password" required>
                        <button type="button" class="password-toggle" data-target="newPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">
                            <i class="fas fa-shield-alt"></i>
                            Password strength: Enter a password
                        </div>
                    </div>
                    <div class="password-requirements">
                        <h4>Password Requirements:</h4>
                        <ul>
                            <li id="req-length"><i class="fas fa-times"></i> At least 8 characters</li>
                            <li id="req-uppercase"><i class="fas fa-times"></i> One uppercase letter</li>
                            <li id="req-lowercase"><i class="fas fa-times"></i> One lowercase letter</li>
                            <li id="req-number"><i class="fas fa-times"></i> One number</li>
                            <li id="req-special"><i class="fas fa-times"></i> One special character</li>
                        </ul>
                    </div>
                    <div class="form-error" id="newPasswordError"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <div class="password-field">
                        <input type="password" id="confirmPassword" name="confirm_password" required>
                        <button type="button" class="password-toggle" data-target="confirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-match" id="passwordMatch"></div>
                    <div class="form-error" id="confirmPasswordError"></div>
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" id="cancelPasswordBtn">Cancel</button>
            <button type="button" class="btn-update" id="updatePasswordBtn">
                <i class="fas fa-lock mr-1"></i> Update Password
            </button>
        </div>
    </div>
</div>

<!-- SUPPORT CONTACT MODAL -->
<div id="supportModal" class="modal hidden">
    <div class="modal-content support-modal">
        <div class="modal-header">
            <h3><i class="fas fa-headset mr-2"></i>Contact Support</h3>
            <button class="modal-close" id="closeSupportModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="supportForm">
                <div class="form-group">
                    <label for="issueCategory">Issue Category</label>
                    <select id="issueCategory" name="issueCategory" required>
                        <option value="">Select an issue category</option>
                        <option value="technical">Technical Issues</option>
                        <option value="course_management">Course Management</option>
                        <option value="student_issues">Student Issues</option>
                        <option value="gradebook">Gradebook Problems</option>
                        <option value="account">Account Problems</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="supportSubject">Subject</label>
                    <input type="text" id="supportSubject" name="supportSubject" required placeholder="Brief description of your issue">
                </div>
                
                <div class="form-group">
                    <label for="supportDescription">Description</label>
                    <textarea id="supportDescription" name="supportDescription" required placeholder="Please provide detailed information about your issue..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="supportAttachment">Attach Files (Optional)</label>
                    <input type="file" id="supportAttachment" name="supportAttachment" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <small>Supported formats: Images, PDF, Word documents (Max 10MB each)</small>
                </div>
            </form>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" id="cancelSupportBtn">Cancel</button>
            <button type="button" class="btn-send" id="sendSupportBtn">
                <i class="fas fa-paper-plane mr-1"></i> Send Support Request
            </button>
        </div>
    </div>
</div>

<!-- LOGOUT CONFIRMATION MODAL -->
<div id="logoutConfirmModal" class="modal hidden">
    <div class="modal-content confirmation-modal">
        <div class="modal-header">
            <h3><i class="fas fa-sign-out-alt mr-2"></i>Confirm Logout</h3>
        </div>
        <div class="modal-body">
            <div class="confirmation-message">
                <i class="fas fa-question-circle text-yellow-500"></i>
                <p>Are you sure you want to log out?</p>
                <small>You will be redirected to the login page.</small>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" id="cancelLogoutBtn">Cancel</button>
            <button type="button" class="btn-confirm" id="confirmLogoutBtn">
                <i class="fas fa-sign-out-alt mr-1"></i> Log Out
            </button>
        </div>
    </div>
</div>

<!-- GENERAL PURPOSE MODAL -->
<div id="customModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-body">
            <div id="modalContent" class="modal-text"></div>
        </div>
        <div class="modal-actions">
            <button id="modalOkBtn" class="btn-primary">OK</button>
            <button id="modalCancelBtn" class="btn-cancel hidden">Cancel</button>
        </div>
    </div>
</div>

</div> <!-- End profile-page-wrapper -->

</x-layout>

