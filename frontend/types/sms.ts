export interface SendCodeResponse {
  message: string;
  success: boolean;
}

export interface VerifyCodeResponse {
  valid: boolean;
  message?: string;
}