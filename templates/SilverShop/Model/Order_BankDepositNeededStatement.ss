<%--  Bank Deposit Needed Statement for top of Order/Invoice --%>
<table id="bank-deposit-needed-statement" cellspacing="0" cellpadding="0" border="0" summary="<%t BankDeposit.Information "Bank Deposit Information" %>" >
	<tbody>
		<tr class="bank-deposit-needed-statement-first">
			<td>
			 	<%t BankDeposit.IntroductionMessageStart "Please deposit " %>
			 		<strong>$TotalOutstanding.Nice</strong>
			 	<%t BankDeposit.IntroductionMessageContinueFirstLine " into" %>
			</td>
		</tr>
		<tr>
			<td>
			 	<%t BankDeposit.BankAccountNumberTitle "Bank Account Number: " %>
			 	<% with SiteConfig %>
			 		<strong>$BankAccountNumber</strong>
			 	<% end_with %>
			</td>
		</tr>
		<tr>
			<td>
			 	<%t BankDeposit.DepositReference "Reference: " %>
			 		<strong>$Reference</strong>
			</td>
		</tr>
		<tr class="bank-deposit-needed-statement-last">
			<td>
			 	<%t BankDeposit.DepositCode "Code: " %>
			 		<strong><%t BankDeposit.YourName "Your name" %></strong>
			</td>
		</tr>
	</tbody>
</table>
