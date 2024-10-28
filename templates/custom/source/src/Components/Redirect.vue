<template>
	<div>
		<label>{{ locale.name }}</label>
		<button type="button" @click.prevent="clickBuy">
			{{ locale.submitButton }}
		</button>
		<p v-if="error" style="color: #920a0a">
			<br>
			Error: {{ error }}
		</p>
	</div>
</template>
<script setup>
const props = defineProps({
	redirectLink: {
		type: String,
		required: true
	},
	sessionUrl: {
		type: String,
		required: true
	},
	orderId: {
		type: Number,
		required: true
	},
	stripeKey: {
		type: String,
		required: true
	},
	locale: {
		type: Object,
		default: () => ({
			name: '',
			submitButton: 'Перейти к оплате'
		}),
	}
});

console.log(props);

const clickBuy = async () => {
	window.location.href = props.redirectLink;
};
</script>

<style lang="scss" scoped>
.card-container {
	max-width: 400px;
	margin: 0 auto;
	padding: 20px;
	background-color: #f8f9fa;
	border-radius: 8px;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

	label {
		display: block;
		margin-bottom: 10px;
		font-size: 16px;
		font-weight: 600;
		color: #333;
	}

	button {
		display: block;
		width: 100%;
		padding: 12px;
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
	}

	p {
		margin-top: 15px;
		font-size: 14px;
		line-height: 1.5;
	}
}
</style>
