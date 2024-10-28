<template>
    <div class="payment-container">
        <p v-if="error" class="error">{{error}}</p>
        <RedirectStripe
			:redirect-link="redirectLink"
            :session-url="sessionUrl"
            :stripe-key="stripeKey"
            :locale="locale.redirect"
            :orderId="orderId"
        ></RedirectStripe>
    </div>
</template>
<script setup>
import 'babel-polyfill';
import { ref } from 'vue';
import RedirectStripe from './Components/Redirect.vue';

const props = defineProps({
	redirectLink: {
		type: String,
		required: true
	},
	sessionUrl: { 
		type: String, 
		required: true 
	},
	stripeKey: { 
		type: String, 
		required: true 
	},
	currency: { 
		type: String, 
		required: true 
	},
	currencyEur: { 
		type: String, 
		required: true 
	},
	amount: { 
		type: Number, 
		required: true 
	},
	amountEur: { 
		type: Number, 
		required: true 
	},
	orderId: { 
		type: Number, 
		required: true 
	},
	stripeClientSecret: String,
	stripeSource: String,
	params: {
		type: Object,
		default: () => ({
			sofort: { redirectUrl: '' },
			giropay: { redirectUrl: '' },
		}),
	},
	modeList: {
		type: Array,
		default: () => [
			{ key: 'redirect', value: 'Redirect' }
		],
	},
	locale: {
		type: Object,
		default: () => ({
			redirect: { 
				name: '', 
				submitButton: 'Перейти к оплате'
			}
		}),
	},
});

const error = ref('');
</script>

<style lang="scss" scoped>
.payment-container {
	max-width: 400px;
	margin: 0 auto;
	padding: 20px;
	background-color: #f8f9fa;
	border-radius: 8px;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

	.error {
		color: #fa755a;
		margin-bottom: 15px;
		font-size: 14px;
	}

	.payment-select {
		width: 100%;
		padding: 10px;
		margin-bottom: 20px;
		border: 1px solid #ced4da;
		border-radius: 4px;
		font-size: 16px;
		background-color: white;
		color: #32325d;
		transition: border-color 0.3s ease;

		&:focus {
			outline: none;
			border-color: #007bff;
		}
	}

	// Общие стили для вложенных компонентов
	::v-deep {
		label {
			display: block;
			margin-bottom: 10px;
			font-size: 16px;
			font-weight: 600;
			color: #333;
		}

		input,
		.StripeElement {
			width: 100%;
			height: 40px;
			padding: 10px 12px;
			color: #32325d;
			background-color: white;
			border: 1px solid #ced4da;
			border-radius: 4px;
			box-shadow: 0 1px 3px 0 #e6ebf1;
			transition: box-shadow 150ms ease;

			&:focus {
				box-shadow: 0 1px 3px 0 #cfd7df;
			}
		}

		button {
			display: block;
			width: 100%;
			padding: 12px;
			margin-top: 20px;
			background-color: #007bff;
			color: #fff;
			border: none;
			border-radius: 4px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: background-color 0.3s ease;

			&:hover {
				background-color: #0056b3;
			}

			&:disabled {
				opacity: 0.8;
				cursor: not-allowed;
			}
		}
	}
}
</style>
