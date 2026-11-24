<?php

use App\Http\Controllers\Admin\AdminActivationController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDepositController;
use App\Http\Controllers\Admin\AdminFundController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminIncomeController;
use App\Http\Controllers\Admin\AdminInvestmentController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminP2PController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminAuthController::class, 'showLogin'])->name('home');

Route::get('/captcha', [AdminAuthController::class, 'captcha'])->name('captcha');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

//forget password
Route::get('/forget-password', [AdminAuthController::class, 'forgetPassword']);
Route::post('/retrivePassword', [AdminAuthController::class, 'retrivePassword'])->name('retrivePassword');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('announcements', AdminAnnouncementController::class)->except(['show']);
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        
        // Addon Data
        Route::get('/addon-data', [AdminMemberController::class, 'AddonData'])->name('addon-data.index');
        Route::get('/addon-data/edit/{id}', [AdminMemberController::class, 'AddonDataEdit'])->name('addon-data.edit');
        Route::post('/AddonDataInsert', [AdminMemberController::class, 'AddonDataInsert'])->name('AddonDataInsert');
        Route::post('/AddonDataUpdate', [AdminMemberController::class, 'AddonDataUpdate'])->name('AddonDataUpdate');
        
         // Dynamic Charges
        Route::get('/deduction', [AdminMemberController::class, 'deduction']);
        Route::post('dedtion', [AdminMemberController::class, 'dedtion'])->name('dedtion');
        
          // Dash Images
        Route::get('/dash-images', [AdminMemberController::class, 'dashImages'])->name('dash-images');
        Route::post('uploadDashImg', [AdminMemberController::class, 'uploadDashImg'])->name('uploadDashImg');
        Route::get('/dash-img/status-change/{id}', [AdminMemberController::class, 'dashImgStatusChange'])->name('dash-images.status-change');
        
        Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
        Route::get('/member-security', [AdminMemberController::class, 'security'])->name('member-security');
        Route::post('/member-security', [AdminMemberController::class, 'securityUpdate'])->name('member-security.update');
        Route::get('member-login/{id}', [AdminMemberController::class, 'memberLogin']);
        Route::get('/members/{member}', [AdminMemberController::class, 'show'])->name('members.show');
        Route::put('/members/{member}', [AdminMemberController::class, 'update'])->name('members.update');
        Route::post('/members/{member}/status', [AdminMemberController::class, 'updateStatus'])->name('members.status');
        
        // wallet address update
        Route::get('/wallet-address', [AdminMemberController::class, 'walletAddress']);
        Route::post('updateWallet', [AdminMemberController::class, 'updateWallet'])->name('updateWallet');
        
        // global pool member 
        Route::get('/poolmember', [AdminMemberController::class, 'poolMember']);
        
        // weekly counts 
        Route::get('/weekly-count', [AdminMemberController::class, 'weeklyCount']);
        
         // Member KYC Management
        Route::get('/new-kyc-requests', [AdminKycController::class, 'kycRequests']);
        Route::get('/verified-kyc-requests', [AdminKycController::class, 'verifiedKyc']);
        Route::post('updateKYC', [AdminKycController::class, 'updateKYC'])->name('updateKYC');
        Route::post('updateAfront', [AdminKycController::class, 'updateAfront'])->name('updateAfront');
        Route::post('updateAback', [AdminKycController::class, 'updateAback'])->name('updateAback');
        Route::post('updateSelfie', [AdminKycController::class, 'updateSelfie'])->name('updateSelfie');
        Route::get('/KYC/view/{id}', [AdminKycController::class, 'kycView']);
        Route::get('/kyc/verify/{id}', [AdminKycController::class, 'kycVerify']);
        Route::get('/kyc/reject/{id}', [AdminKycController::class, 'kycReject']);
        
        Route::get('/deposits/settings', [AdminDepositController::class, 'settings'])->name('deposits.settings');
        Route::post('/deposits/settings', [AdminDepositController::class, 'updateSettings'])->name('deposits.settings.update');
        Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
        Route::post('/deposits/payment-details', [AdminDepositController::class, 'storePaymentDetails'])->name('deposits.payment-details.store');
        Route::get('/deposits/reports', [AdminDepositController::class, 'reports'])->name('deposits.reports');
        Route::post('/deposits/{deposit}/approve', [AdminDepositController::class, 'approve'])->name('deposits.approve');
        Route::post('/deposits/{deposit}/reject', [AdminDepositController::class, 'reject'])->name('deposits.reject');
        Route::get('/funds/add', [AdminFundController::class, 'addFundsPage'])->name('funds.add');
        Route::post('/funds/add', [AdminFundController::class, 'addMemFunds'])->name('funds.add.submit');
        Route::get('/funds/deduct', [AdminFundController::class, 'deductFundsPage'])->name('funds.deduct');
        Route::post('/funds/deduct', [AdminFundController::class, 'deductMemFunds'])->name('funds.deduct.submit');
        Route::post('/withdrawal/quick-accept', [AdminWithdrawalController::class, 'quickAccept'])->name('withdrawal.quick-accept');
        Route::get('/funds/details', [AdminFundController::class, 'fundsDetails'])->name('funds.details');
        Route::get('/funds/requests/new', [AdminFundController::class, 'newFundRequests'])->name('funds.requests.new');
        Route::get('/funds/fund/details', [AdminFundController::class, 'ImportFundDetails'])->name('funds.fund.details');
        Route::get('/funds/requests/details', [AdminFundController::class, 'fundRequestDetails'])->name('funds.requests.details');
        Route::post('/funds/requests/approve/{deposit}', [AdminFundController::class, 'approveFundRequest'])->name('funds.requests.approve');
        Route::post('/funds/requests/accept/{id}', [AdminFundController::class, 'acceptFundRequest'])->name('funds.requests.accept');
        Route::post('/funds/requests/reject/{id}', [AdminFundController::class, 'rejectFundRequest'])->name('funds.requests.reject');
        Route::get('/payment/new-withdrawal-requests', [AdminPaymentController::class, 'newWithdrawalRequests'])->name('payment.requests.new');
        Route::get('/payment/cancelled-requests', [AdminPaymentController::class, 'cancelledRequests'])->name('payment.requests.cancelled');
        Route::get('/payment/history', [AdminPaymentController::class, 'paymentHistory'])->name('payment.history');
        Route::post('/withdrawal/accept', [AdminWithdrawalController::class, 'wAccept'])->name('withdrawal.accept');
        Route::post('/withdrawal/cancel', [AdminWithdrawalController::class, 'wCancel'])->name('withdrawal.cancel');
        Route::get('/activations', [AdminActivationController::class, 'index'])->name('activations.index');
        Route::get('/activations/upgrades', [AdminActivationController::class, 'upgrades'])->name('activations.upgrades');
        Route::get('/activations/package-history', [AdminActivationController::class, 'packageHistory'])->name('activations.package-history');
        Route::get('/investments', [AdminInvestmentController::class, 'index'])->name('investments.index');
        Route::get('/investments/roi-distribution', [AdminInvestmentController::class, 'roiDistribution'])->name('investments.roi-distribution');
        Route::get('/investments/roi-schedule', [AdminInvestmentController::class, 'roiSchedule'])->name('investments.roi-schedule');
        Route::get('/investments/reports', [AdminInvestmentController::class, 'reports'])->name('investments.reports');
        Route::get('/incomes/community', [AdminIncomeController::class, 'community'])->name('incomes.community');
        Route::get('/incomes/rank', [AdminIncomeController::class, 'rankIncome'])->name('incomes.rank');
        Route::get('/incomes/rank/manual-transfer', [AdminIncomeController::class, 'rankIncomeManualTransfer'])->name('incomes.rank.manual-transfer');
        Route::post('/incomes/rank/manual-transfer', [AdminIncomeController::class, 'storeRankIncomeManualTransfer'])->name('incomes.rank.manual-transfer.store');
        Route::get('/incomes/staking', [AdminIncomeController::class, 'stakingIncome'])->name('incomes.staking');
        Route::get('/incomes/direct', [AdminIncomeController::class, 'directIncome'])->name('incomes.direct');
        Route::get('/incomes/level', [AdminIncomeController::class, 'levelIncome'])->name('incomes.level');
        Route::get('/incomes/global-rec', [AdminIncomeController::class, 'globalRec'])->name('incomes.global-rec');
        Route::get('/incomes/global-rec-star', [AdminIncomeController::class, 'globalRecStar'])->name('incomes.global-rec-star');
        Route::get('/incomes/roi', [AdminIncomeController::class, 'roiIncomeDistribution'])->name('incomes.roi');
        Route::post('/incomes/roi', [AdminIncomeController::class, 'roiIncomeDistribute'])->name('incomes.roi.distribute');
        Route::get('/incomes/sponsor-roi', [AdminIncomeController::class, 'sponsorRoi'])->name('incomes.sponsor-roi');
        Route::get('/p2p/member-wallets', [AdminP2PController::class, 'memberWallets'])->name('p2p.member-wallets');
        Route::get('/p2p/wallet-ledger', [AdminP2PController::class, 'walletLedger'])->name('p2p.wallet-ledger');
        Route::get('/p2p/wallet-adjustments', [AdminP2PController::class, 'walletAdjustments'])->name('p2p.wallet-adjustments');
        Route::get('/p2p/wallet-reports', [AdminP2PController::class, 'walletReports'])->name('p2p.wallet-reports');
        Route::get('/p2p/wallet-audit-logs', [AdminP2PController::class, 'walletAuditLogs'])->name('p2p.wallet-audit-logs');
        Route::get('/withdrawals/settings', [AdminWithdrawalController::class, 'settings'])->name('withdrawals.settings');
        Route::post('/withdrawals/settings', [AdminWithdrawalController::class, 'updateSettings'])->name('withdrawals.settings.update');
        Route::get('/withdrawals/requests', [AdminWithdrawalController::class, 'requests'])->name('withdrawals.requests');
        Route::get('/withdrawals/approved', [AdminWithdrawalController::class, 'approved'])->name('withdrawals.approved');
        Route::get('/withdrawals/cancelled', [AdminWithdrawalController::class, 'cancelled'])->name('withdrawals.cancelled');
        Route::get('/withdrawals/reports', [AdminWithdrawalController::class, 'reports'])->name('withdrawals.reports');
        Route::get('/withdrawals/private-keys', [AdminWithdrawalController::class, 'privateKeys'])->name('withdrawals.private-keys');
        Route::get('/support/new-support-tickets', [AdminSupportController::class, 'newSupport'])->name('support.new-tickets');
        Route::get('/support/open-support-tickets', [AdminSupportController::class, 'openSupport'])->name('support.open-tickets');
        Route::get('/support/close-support-tickets', [AdminSupportController::class, 'closeSupport'])->name('support.close-tickets');
        Route::get('/support/view/{id}', [AdminSupportController::class, 'viewSupport'])->name('support.view');
        Route::post('/support/reply/{id}', [AdminSupportController::class, 'replySupport'])->name('support.reply');
        Route::post('/support/close/{id}', [AdminSupportController::class, 'closeTicket'])->name('support.close');
        Route::get('/support/ticket/{support}', [AdminSupportController::class, 'show'])->name('support.show');
        Route::get('/support/open', [AdminSupportController::class, 'open'])->name('support.open');
        Route::get('/support/closed', [AdminSupportController::class, 'closed'])->name('support.closed');
        Route::get('/count', [AdminSettingController::class, 'index'])->name('count');
        Route::post('/count', [AdminSettingController::class, 'update'])->name('count.update');
        
         Route::post('/getMember', [AdminMemberController::class, 'getMember']);
    });
});


Route::fallback(function () {
    return view('admin.error404');
});
