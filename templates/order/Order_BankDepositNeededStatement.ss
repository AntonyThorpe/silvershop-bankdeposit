<%--  Bank Deposit Needed Statement for top of Order/Invoice --%>
<table id="bank-deposit-needed-statement" cellspacing="0" cellpadding="0" border="0" summary="<% _t('BankDepositInformation','Bank Deposit Information') %>" >
	<tbody>
		<tr class="bank-deposit-needed-statement-first">
			<td>
			 	<% _t("IntroductionMessageStart","Please deposit ") %>
			 		<strong>$TotalOutstanding.Nice</strong>
			 	<% _t("IntroductionMessageContinueFirstLine"," into") %>
			</td>
		</tr>
		<tr>
			<td>
			 	<% _t("BankAccountNumberTitle", "Bank Account Number: ") %>
			 	<% with SiteConfig %>
			 		<strong>$BankAccountNumber</strong>
			 	<% end_with %>
			</td>
		</tr>
		<tr>
			<td>
			 	<% _t("DepositReference", "Reference: ") %>
			 		<strong>$Reference</strong>
			</td>
		</tr>
		<tr class="bank-deposit-needed-statement-last">
			<td>
			 	<% _t("DepositCode", "Code: ") %>
			 		<strong><% _t("YourName","Your name") %></strong>
			</td>
		</tr>
	</tbody>
</table>
